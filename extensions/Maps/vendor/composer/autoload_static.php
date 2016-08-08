<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7d0f818300453bfa898c5a9575c6ce60
{
    public static $files = array (
        'd1715cacc3c23b16a030645514266a76' => __DIR__ . '/..' . '/data-values/interfaces/Interfaces.php',
        '7cb394c3af2b1ae832979b0368e0da62' => __DIR__ . '/..' . '/data-values/data-values/DataValues.php',
        '90559502573a0d473dc66fde5c0ff7e2' => __DIR__ . '/..' . '/data-values/common/Common.php',
        '0dd9431cbbfa9ed9cb9d565d7129dbaf' => __DIR__ . '/..' . '/data-values/validators/Validators.php',
        'af3cc937b8a54e5b4209c82d6cfe8889' => __DIR__ . '/..' . '/param-processor/param-processor/DefaultConfig.php',
        'c3ae67574219cc56cab6c30ef8877b85' => __DIR__ . '/../..' . '/extensions/Validator/Validator.php',
        '5a494680c593293bd6035e42e2a6825c' => __DIR__ . '/..' . '/data-values/geo/Geo.php',
        '9ebf2cbcc0b7687b276c44d77096b002' => __DIR__ . '/../..' . '/Maps.php',
    );

    public static $prefixLengthsPsr4 = array (
        'V' => 
        array (
            'ValueValidators\\' => 16,
            'ValueParsers\\' => 13,
            'ValueFormatters\\' => 16,
        ),
        'P' => 
        array (
            'ParamProcessor\\' => 15,
        ),
        'M' => 
        array (
            'Maps\\' => 5,
        ),
        'D' => 
        array (
            'DataValues\\Geo\\' => 15,
            'DataValues\\' => 11,
        ),
        'C' => 
        array (
            'Composer\\Installers\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'ValueValidators\\' => 
        array (
            0 => __DIR__ . '/..' . '/data-values/interfaces/src/ValueValidators',
            1 => __DIR__ . '/..' . '/data-values/validators/src',
        ),
        'ValueParsers\\' => 
        array (
            0 => __DIR__ . '/..' . '/data-values/interfaces/src/ValueParsers',
            1 => __DIR__ . '/..' . '/data-values/common/src/ValueParsers',
        ),
        'ValueFormatters\\' => 
        array (
            0 => __DIR__ . '/..' . '/data-values/interfaces/src/ValueFormatters',
            1 => __DIR__ . '/..' . '/data-values/common/src/ValueFormatters',
        ),
        'ParamProcessor\\' => 
        array (
            0 => __DIR__ . '/..' . '/param-processor/param-processor/src',
            1 => __DIR__ . '/../..' . '/extensions/Validator/src/ParamProcessor',
        ),
        'Maps\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Maps',
        ),
        'DataValues\\Geo\\' => 
        array (
            0 => __DIR__ . '/..' . '/data-values/geo/src',
        ),
        'DataValues\\' => 
        array (
            0 => __DIR__ . '/..' . '/data-values/common/src/DataValues',
        ),
        'Composer\\Installers\\' => 
        array (
            0 => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers',
        ),
    );

    public static $prefixesPsr0 = array (
        'D' => 
        array (
            'DataValues\\' => 
            array (
                0 => __DIR__ . '/..' . '/data-values/data-values/src',
            ),
        ),
    );

    public static $classMap = array (
        'Comparable' => __DIR__ . '/..' . '/data-values/data-values/src/interfaces/Comparable.php',
        'Copyable' => __DIR__ . '/..' . '/data-values/data-values/src/interfaces/Copyable.php',
        'CriterionIsNonNumeric' => __DIR__ . '/../..' . '/includes/criteria/CriterionIsNonNumeric.php',
        'CriterionMapLayer' => __DIR__ . '/../..' . '/includes/criteria/CriterionMapLayer.php',
        'CriterionOLLayer' => __DIR__ . '/../..' . '/includes/services/OpenLayers/CriterionOLLayer.php',
        'DataValues\\Tests\\DataValueTest' => __DIR__ . '/..' . '/data-values/data-values/tests/phpunit/DataValueTest.php',
        'Hashable' => __DIR__ . '/..' . '/data-values/data-values/src/interfaces/Hashable.php',
        'Immutable' => __DIR__ . '/..' . '/data-values/data-values/src/interfaces/Immutable.php',
        'MapEditorHtml' => __DIR__ . '/../..' . '/includes/editor/MapEditorHTML.php',
        'MapsBaseFillableElement' => __DIR__ . '/../..' . '/includes/Maps_BaseFillableElement.php',
        'MapsBaseStrokableElement' => __DIR__ . '/../..' . '/includes/Maps_BaseStrokableElement.php',
        'MapsCoordinates' => __DIR__ . '/../..' . '/includes/parserhooks/Maps_Coordinates.php',
        'MapsDisplayMap' => __DIR__ . '/../..' . '/includes/parserhooks/Maps_DisplayMap.php',
        'MapsDisplayMapRenderer' => __DIR__ . '/../..' . '/includes/Maps_DisplayMapRenderer.php',
        'MapsDistance' => __DIR__ . '/../..' . '/includes/parserhooks/Maps_Distance.php',
        'MapsDistanceParser' => __DIR__ . '/../..' . '/includes/Maps_DistanceParser.php',
        'MapsFinddestination' => __DIR__ . '/../..' . '/includes/parserhooks/Maps_Finddestination.php',
        'MapsGeoFunctions' => __DIR__ . '/../..' . '/includes/Maps_GeoFunctions.php',
        'MapsGeocode' => __DIR__ . '/../..' . '/includes/parserhooks/Maps_Geocode.php',
        'MapsGeocoderusGeocoder' => __DIR__ . '/../..' . '/includes/geocoders/Maps_GeocoderusGeocoder.php',
        'MapsGeodistance' => __DIR__ . '/../..' . '/includes/parserhooks/Maps_Geodistance.php',
        'MapsGeonamesGeocoder' => __DIR__ . '/../..' . '/includes/geocoders/Maps_GeonamesGeocoder.php',
        'MapsGoogleGeocoder' => __DIR__ . '/../..' . '/includes/geocoders/Maps_GoogleGeocoder.php',
        'MapsGoogleMaps3' => __DIR__ . '/../..' . '/includes/services/GoogleMaps3/Maps_GoogleMaps3.php',
        'MapsHooks' => __DIR__ . '/../..' . '/Maps.hooks.php',
        'MapsImageLayer' => __DIR__ . '/../..' . '/includes/layers/Maps_ImageLayer.php',
        'MapsKMLFormatter' => __DIR__ . '/../..' . '/includes/Maps_KMLFormatter.php',
        'MapsLayer' => __DIR__ . '/../..' . '/includes/Maps_Layer.php',
        'MapsLayerDefinition' => __DIR__ . '/../..' . '/includes/parserhooks/Maps_LayerDefinition.php',
        'MapsLayerGroup' => __DIR__ . '/../..' . '/includes/Maps_LayerGroup.php',
        'MapsLayerPage' => __DIR__ . '/../..' . '/includes/Maps_LayerPage.php',
        'MapsLayerTypes' => __DIR__ . '/../..' . '/includes/Maps_LayerTypes.php',
        'MapsLayers' => __DIR__ . '/../..' . '/includes/Maps_Layers.php',
        'MapsLeaflet' => __DIR__ . '/../..' . '/includes/services/Leaflet/Maps_Leaflet.php',
        'MapsMapper' => __DIR__ . '/../..' . '/includes/Maps_Mapper.php',
        'MapsMappingService' => __DIR__ . '/../..' . '/includes/Maps_MappingService.php',
        'MapsMappingServices' => __DIR__ . '/../..' . '/includes/Maps_MappingServices.php',
        'MapsMapsDoc' => __DIR__ . '/../..' . '/includes/parserhooks/Maps_MapsDoc.php',
        'MapsOpenLayers' => __DIR__ . '/../..' . '/includes/services/OpenLayers/Maps_OpenLayers.php',
        'MapsParamLayerDefinition' => __DIR__ . '/../..' . '/includes/manipulations/Maps_ParamLayerDefinition.php',
        'MapsParamOLLayers' => __DIR__ . '/../..' . '/includes/services/OpenLayers/Maps_ParamOLLayers.php',
        'MapsParamSwitchIfGreaterThan' => __DIR__ . '/../..' . '/includes/manipulations/Maps_ParamSwitchIfGreaterThan.php',
        'Maps\\Api\\Geocode' => __DIR__ . '/../..' . '/includes/api/ApiGeocode.php',
        'Maps\\CircleParser' => __DIR__ . '/../..' . '/includes/parsers/CircleParser.php',
        'Maps\\DistanceParser' => __DIR__ . '/../..' . '/includes/parsers/DistanceParser.php',
        'Maps\\Element' => __DIR__ . '/../..' . '/includes/Element.php',
        'Maps\\ElementOptions' => __DIR__ . '/../..' . '/includes/Element.php',
        'Maps\\Geocoder' => __DIR__ . '/../..' . '/includes/Geocoder.php',
        'Maps\\Geocoders' => __DIR__ . '/../..' . '/includes/Geocoders.php',
        'Maps\\ImageOverlayParser' => __DIR__ . '/../..' . '/includes/parsers/ImageOverlayParser.php',
        'Maps\\LineParser' => __DIR__ . '/../..' . '/includes/parsers/LineParser.php',
        'Maps\\LocationParser' => __DIR__ . '/../..' . '/includes/parsers/LocationParser.php',
        'Maps\\OptionsObject' => __DIR__ . '/../..' . '/includes/Element.php',
        'Maps\\PolygonParser' => __DIR__ . '/../..' . '/includes/parsers/PolygonParser.php',
        'Maps\\RectangleParser' => __DIR__ . '/../..' . '/includes/parsers/RectangleParser.php',
        'Maps\\ServiceParam' => __DIR__ . '/../..' . '/includes/ServiceParam.php',
        'Maps\\Test\\ParserHookTest' => __DIR__ . '/../..' . '/tests/phpunit/parserhooks/ParserHookTest.php',
        'Maps\\Tests\\Elements\\BaseElementTest' => __DIR__ . '/../..' . '/tests/phpunit/elements/BaseElementTest.php',
        'Maps\\Tests\\Elements\\CircleTest' => __DIR__ . '/../..' . '/tests/phpunit/elements/CircleTest.php',
        'Maps\\Tests\\Elements\\ImageOverlayTest' => __DIR__ . '/../..' . '/tests/phpunit/elements/ImageOverlayTest.php',
        'Maps\\Tests\\Elements\\LineTest' => __DIR__ . '/../..' . '/tests/phpunit/elements/LineTest.php',
        'Maps\\Tests\\Elements\\LocationTest' => __DIR__ . '/../..' . '/tests/phpunit/elements/LocationTest.php',
        'Maps\\Tests\\Elements\\PolygonTest' => __DIR__ . '/../..' . '/tests/phpunit/elements/PolygonTest.php',
        'Maps\\Tests\\Elements\\RectangleTest' => __DIR__ . '/../..' . '/tests/phpunit/elements/RectangleTest.php',
        'Maps\\WmsOverlayParser' => __DIR__ . '/../..' . '/includes/parsers/WmsOverlayParser.php',
        'ParamProcessor\\Tests\\Definitions\\NumericParamTest' => __DIR__ . '/..' . '/param-processor/param-processor/tests/phpunit/Definitions/NumericParamTest.php',
        'ParamProcessor\\Tests\\Definitions\\ParamDefinitionTest' => __DIR__ . '/..' . '/param-processor/param-processor/tests/phpunit/Definitions/ParamDefinitionTest.php',
        'ParserHook' => __DIR__ . '/../..' . '/extensions/Validator/src/legacy/ParserHook.php',
        'ParserHookCaller' => __DIR__ . '/../..' . '/extensions/Validator/src/legacy/ParserHook.php',
        'SpecialMapEditor' => __DIR__ . '/../..' . '/includes/specials/SpecialMapEditor.php',
        'ValueFormatters\\Test\\ValueFormatterTestBase' => __DIR__ . '/..' . '/data-values/interfaces/tests/ValueFormatters/ValueFormatterTestBase.php',
        'ValueParsers\\Normalizers\\Test\\NullStringNormalizerTest' => __DIR__ . '/..' . '/data-values/common/tests/ValueParsers/Normalizers/NullStringNormalizerTest.php',
        'ValueParsers\\Test\\BoolParserTest' => __DIR__ . '/..' . '/data-values/common/tests/ValueParsers/BoolParserTest.php',
        'ValueParsers\\Test\\DispatchingValueParserTest' => __DIR__ . '/..' . '/data-values/common/tests/ValueParsers/DispatchingValueParserTest.php',
        'ValueParsers\\Test\\FloatParserTest' => __DIR__ . '/..' . '/data-values/common/tests/ValueParsers/FloatParserTest.php',
        'ValueParsers\\Test\\IntParserTest' => __DIR__ . '/..' . '/data-values/common/tests/ValueParsers/IntParserTest.php',
        'ValueParsers\\Test\\NullParserTest' => __DIR__ . '/..' . '/data-values/common/tests/ValueParsers/NullParserTest.php',
        'ValueParsers\\Test\\StringParserTest' => __DIR__ . '/..' . '/data-values/common/tests/ValueParsers/StringParserTest.php',
        'ValueParsers\\Test\\StringValueParserTest' => __DIR__ . '/..' . '/data-values/common/tests/ValueParsers/StringValueParserTest.php',
        'ValueParsers\\Test\\ValueParserTestBase' => __DIR__ . '/..' . '/data-values/common/tests/ValueParsers/ValueParserTestBase.php',
        'iBubbleMapElement' => __DIR__ . '/../..' . '/includes/properties/iBubbleMapElement.php',
        'iFillableMapElement' => __DIR__ . '/../..' . '/includes/properties/iFillableMapElement.php',
        'iHoverableMapElement' => __DIR__ . '/../..' . '/includes/properties/iHoverableMapElement.php',
        'iLinkableMapElement' => __DIR__ . '/../..' . '/includes/properties/iLinkableMapElement.php',
        'iMappingService' => __DIR__ . '/../..' . '/includes/iMappingService.php',
        'iStrokableMapElement' => __DIR__ . '/../..' . '/includes/properties/iStrokableMapElement.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7d0f818300453bfa898c5a9575c6ce60::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7d0f818300453bfa898c5a9575c6ce60::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit7d0f818300453bfa898c5a9575c6ce60::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit7d0f818300453bfa898c5a9575c6ce60::$classMap;

        }, null, ClassLoader::class);
    }
}
