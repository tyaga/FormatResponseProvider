<?
namespace FormatResponse\Silex\Provider;

use Silex\Application,
	Silex\ServiceProviderInterface,
	Symfony\Component\HttpFoundation\Response,
	Symfony\Component\HttpKernel\Exception\HttpException;

class FormatResponseProvider implements ServiceProviderInterface {

	public function register(Application $app) {
		$app['render'] = $app->share(function () use ($app) {
			$formatter = new RenderResponseFormatter($app);
			return $formatter;
		});
	}
}

class RenderResponseFormatter {
	private function prepare($response, $code) {

		if ($code == 200) {
			$result = array (
				'res' => true,
				'response' => $response
			);
		}
		else {
			$result = array (
				'res' => false,
				'error' => $response,
				'code'  => $code
			);
		}
		return $result;
	}

	public function format($response, $format = 'json', $code = 200) {
		$response = $this->prepare($response, $code);

		switch($format) {
			case 'debug':

				return new Response('<pre>'.var_export($response, true).'</pre>');
				break;

			case 'json':

				return new Response(json_encode($response), $code, array('Content-Type' => 'application/json'));
				break;

			case 'xml':
				$xml = new \SimpleXMLElement('<root/>');
				array_walk_recursive($response, array ($xml, 'addChild'));
				return new Response($xml->asXML(), $code, array('Content-Type' => 'application/xml'));
				break;

			default:
				throw new HttpException(500, 'Undefined format `' . $format . '`');

		}
	}

}