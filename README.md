# FormatResponseProvider

## Register provider

	use \Silex\Provider\FormatResponseProvider;

	$app->register(new FormatResponseProvider());

## Render

	$app->get('/api.{format}', function($format) use ($app) {

		return $app['render']->format(array('status'=>'success'), $format);

	})->assert('format', 'json|xml|debug');

## Authors:

* [Alexey Tyagunov](mailto:atyaga@gmail.com)

