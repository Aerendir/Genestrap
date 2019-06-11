/* This to avoid errors about "wp" is not defined */
/* eslint-disable no-undef */
/* This is to avoid errors about "call" is not defined */
/* eslint-disable no-unused-vars */
const el = wp.element.createElement,
	{ __ } = wp.i18n,
	{ apiFetch } = wp,
	{ registerBlockType } = wp.blocks,
	{ PanelBody, RangeControl, QueryControls, Spinner, ToggleControl, Toolbar } = wp.components,
	{ withSelect } = wp.data,
	{ dateI18n, format } = wp.date,
	{ BlockAlignmentToolbar, BlockControls, InspectorControls } = wp.editor,
	{ Fragment } = wp.element,
	{ decodeEntities } = wp.htmlEntities,
	{ addQueryArgs } = wp.url;

// These calla have to stay here so they get a value before the block and its Inspector is rendered
// https://wordpress.stackexchange.com/questions/319035/how-would-i-get-a-taxonomy-category-list-inside-a-gutenberg-block#comment473739_320481
const categoriesList = [];
const call = apiFetch( { path: addQueryArgs( '/wp/v2/categories', { per_page: -1 } ) } )
	.then( function( categories ) {
		categories.map( function( category ) {
			categoriesList.push( category );
			return true;
		} );
	} );
const MAX_POSTS_COLUMNS = 6;

/**
 * Checks if there are posts to display or not.
 *
 * @param {Array|null} posts - The list of posts.
 * @return {boolean} - If has posts or not.
 */
const hasPosts = function( posts ) {
	return Array.isArray( posts ) && posts.length;
};

/**
 * Builds the controls to display in the block's toolbar.
 *
 * @param {Object} props - The props of the React component.
 *
 * @return {function} - The toolbar.
 */
const buildToolbarControls = function( props ) {
	const { attributes, setAttributes } = props;
	const { align, postLayout } = attributes;

	const onChangeBlockAlignment = function( nextAlign ) {
		setAttributes( { align: nextAlign } );
	};
	const blockAlignmentToolbarElement = el( BlockAlignmentToolbar, {
		align,
		onChangeBlockAlignment,
		controls: [ 'center', 'wide', 'full' ],
	} );

	const toolbarControls = [
		{
			icon: 'list-view',
			title: __( 'List View' ),
			onClick() {
				setAttributes( { postLayout: 'list' } );
			},
			isActive: 'list' === postLayout,
		},
		{
			icon: 'grid-view',
			title: __( 'Grid View' ),
			onClick() {
				setAttributes( { postLayout: 'grid' } );
			},
			isActive: 'grid' === postLayout,
		},
	];
	const toolbarElement = el( Toolbar, { controls: toolbarControls } );

	return el( BlockControls, null, blockAlignmentToolbarElement, toolbarElement );
};

/**
 * Build the Controls to display in the Block's Inspector.
 *
 * @param {Object} props - The props of the React component.
 *
 * @return {function} - The block controls.
 */
const buildInspectorControls = function( props ) {
	const { attributes, posts, setAttributes } = props;
	const { columns, displayPostDate, displayPostFeaturedImage, order, orderBy, numberOfItems, postLayout, selectedCategoryId } = attributes;

	const onOrderChange = function( value ) {
		setAttributes( { order: value } );
	};
	const onOrderByChange = function( value ) {
		setAttributes( { orderBy: value } );
	};
	const onCategoryChange = function( value ) {
		setAttributes( { selectedCategoryId: '' === value ? undefined : value } );
	};
	const onNumberOfItemsChange = function( value ) {
		setAttributes( { numberOfItems: value } );
	};

	// https://github.com/WordPress/gutenberg/tree/master/packages/components/src/query-controls
	const queryControlsElement = el( QueryControls, {
		categoriesList,
		selectedCategoryId,
		order,
		orderBy,
		numberOfItems,
		onOrderChange,
		onOrderByChange,
		onNumberOfItemsChange,
		onCategoryChange,
	} );

	const onDisplayPostDateChange = function() {
		setAttributes( { displayPostDate: ! displayPostDate } );
	};
	const toggleDisplayPostDateLabel = __( 'Display post date' );
	const toggleDisplayPostDateElement = el( ToggleControl, {
		label: toggleDisplayPostDateLabel,
		checked: displayPostDate,
		onChange: onDisplayPostDateChange,
	} );

	const onDisplayPostFeaturedImageChange = function() {
		setAttributes( { displayPostFeaturedImage: ! displayPostFeaturedImage } );
	};
	const toggleDisplayPostFeaturedImageLabel = __( 'Display post featured image' );
	const toggleDisplayPostFeaturedImageElement = el( ToggleControl, {
		label: toggleDisplayPostFeaturedImageLabel,
		checked: displayPostFeaturedImage,
		onChange: onDisplayPostFeaturedImageChange,
	} );

	let postLayoutElement = '';
	if ( 'grid' === postLayout ) {
		const postLayoutLabel = __( 'Columns' );
		const onChangePostLayout = function( value ) {
			setAttributes( { columns: value } );
		};
		const postLayoutMaxColumns = ! hasPosts( posts ) ? MAX_POSTS_COLUMNS : Math.min( MAX_POSTS_COLUMNS, posts.length );

		postLayoutElement = el( RangeControl, {
			label: postLayoutLabel,
			value: columns,
			onChange: onChangePostLayout,
			min: 2,
			max: postLayoutMaxColumns,
		} );
	}

	const panelBodyElement = el( PanelBody, { title: 'Questo è il titolo' }, queryControlsElement, toggleDisplayPostDateElement, toggleDisplayPostFeaturedImageElement, postLayoutElement );

	return el( InspectorControls, null, panelBodyElement );
};

/**
 * Renders the post entry.
 *
 * @param {Object} post - The post to render
 * @param {boolean} displayPostDate - Show or not the date of the post.
 * @param {boolean} displayPostFeaturedImage - Show or not the featured image of the post.
 * @return {function} - The post entry.
 */
const renderPostEntry = function( post, displayPostDate, displayPostFeaturedImage ) {
	const title = decodeEntities( post.title.rendered.trim() || __( '(Untitled)' ) );
	const titleElement = el( 'a', { href: post.link }, title );

	let dateElement = '';
	if ( true === displayPostDate && post.date_gmt ) {
		// dateI18n first parameter is equal to wp-date.__experimentalGetSettings().formats.date
		dateElement = el( 'time', { dateTime: format( 'c', post.date_gmt ), className: 'wp-block-genestrap-latest-posts__post-date' }, dateI18n( 'F j, Y', post.date_gmt ) );
	}

	let featuredImageElement = '';
	if ( true === displayPostFeaturedImage && post._embedded && post._embedded[ 'wp:featuredmedia' ] && post._embedded[ 'wp:featuredmedia' ][ 0 ] ) {
		featuredImageElement = el( 'img', { className: 'wp-block-latest-posts__post-featured-image', src: post._embedded[ 'wp:featuredmedia' ][ 0 ].source_url } );
	}

	return el( Fragment, null, featuredImageElement, titleElement, dateElement );
};

/**
 * Build the list of posts.
 *
 * @param {Object} props - The props passed to the component.
 * @return {function} - The list of posts.
 */
const buildPostsList = function( props ) {
	const { className, posts, attributes } = props;
	const { columns, displayPostDate, postLayout } = attributes;

	const classes = [];
	classes.push( className );

	if ( 'grid' === postLayout ) {
		classes.push( 'is-grid' );
		classes.push( `columns-${ columns }` );
	}

	if ( true === displayPostDate ) {
		classes.push( 'has-dates' );
	}

	// Build the lists of Posts to show
	const postsElements = posts.map( function( post ) {
		const renderedPost = renderPostEntry( post, displayPostDate, attributes.displayPostFeaturedImage );

		return el( 'li', null, renderedPost );
	} );

	return el( 'ul', { className: classes.join( ' ' ) }, postsElements );
};

registerBlockType( 'genestrap/latest-posts', {
	title: 'Latest Posts Ecom',

	icon: 'dashicons-list-view',

	category: 'widgets',

	edit: withSelect( function( select, ownProps ) {
		const { selectedCategoryId, orderBy, order } = ownProps.attributes;
		const numberOfItems = Number.isInteger( ownProps.attributes.numberOfItems ) ? ownProps.attributes.numberOfItems : 5;

		return { posts: select( 'core' ).getEntityRecords( 'postType', 'post', { per_page: numberOfItems, order, orderBy, categories: selectedCategoryId, _embed: true } ) };
	} )( function( props ) {
		if ( ! props.posts ) {
			return Spinner();
		}

		if ( props.posts.length === 0 ) {
			return __( 'No posts found.' );
		}

		const postsList = buildPostsList( props );

		return ( el( Fragment, null, buildToolbarControls( props ), buildInspectorControls( props ), postsList ) );
	} ),

	save() {
		return null;
	},
} );
