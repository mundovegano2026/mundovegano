import 'ol/ol.css';
import {Map, View, Feature} from 'ol';
import Control from 'ol/control/Control';
import {Style, Circle, Fill, Stroke, Icon, IconOptions} from 'ol/style';
import TileLayer from 'ol/layer/Tile';
import VectorImage from 'ol/layer/VectorImage';
import Vector from 'ol/source/Vector';
import GeoJSON from 'ol/format/GeoJSON';
import OSM from 'ol/source/OSM';
import Point from 'ol/geom/Point';
import {transform} from 'ol/proj';
import { map } from 'lodash';
import proj4 from 'proj4';
import {register} from 'ol/proj/proj4';
import {get as getProjection} from 'ol/proj';

var olMap = {   
    mapObj: null,    
    transform: transform,
    iconDefaultStyle: new Style({
        image: new Icon({
            anchor: [0.5, 0.8],
            anchorXUnits: 'fraction',
            anchorYUnits: 'fraction',
            src: '/storage/img/app/icon_50.png',
        }),
    }),    
    display: function (target) {          

        var that = this;
        target = typeof target == 'undefined' ? 'osm_map' : target;

        //const center = transform([-8.716109038122063, 39.17074301114271], 'EPSG:4326', 'EPSG:38574326');

        // To use other projections, you have to register the projection in OpenLayers.
        // This can easily be done with [https://proj4js.org](proj4)
        //
        // By default OpenLayers does not know about the EPSG:21781 (Swiss) projection.
        // So we create a projection instance for EPSG:21781 and pass it to
        // register to make it available to the library for lookup by its
        // code.
        proj4.defs("EPSG:3763","+proj=tmerc +lat_0=39.66825833333333 +lon_0=-8.133108333333334 +k=1 +x_0=0 +y_0=0 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs");
        register(proj4);
        const ptProjection = getProjection('EPSG:3763');

        this.mapObj = new Map({
            target: target,
            layers: [
                new TileLayer({
                    source: new OSM()
                })
            ],
            view: new View({
                //center: [-8.716109038122063, 39.17074301114271],
                //center: center,
                center: [0, 0],
                zoom: 7,
                projection: ptProjection
            }),
            //projection: 'EPSG:3763'
            
        });
        
        // this.mapObj.on('click', function(e) {
      
        // });

        window["map"] = this.mapObj;
        
        return this.mapObj;
    },
    addLayer: function(layerName, map, layer) {

        const ptProjection = getProjection('EPSG:3763');
        if(typeof layer != 'undefined') {
            var pt = new Point(layer);
            var feat = new Feature({
                geometry: pt,
                //projection: 'EPSG:3763'
                //projection: 'EPSG:4258'
                projection: ptProjection
            });        
            feat.setStyle(this.iconDefaultStyle);
        }

        const vectorList = new VectorImage({
            source: new Vector({
                //url: '/storage/maps/map.geojson',
                //format: new GeoJSON()
                //features: [{"type":"Feature","properties":{},"geometry":{"type":"Point","coordinates":[-9.128952026367188,38.72248334545944]}}],
                //format: new GeoJSON()
            }),
            visible: true,
            title: layerName
        });
        if(typeof layer != 'undefined') {
            vectorList.getSource().addFeature(feat);
        }

        map.addLayer(vectorList);
        var extent = vectorList.getSource().getExtent();
        if(extent[0]!="Infinity") {
            map.getView().fit(extent,map.getSize());
            map.getView().setZoom(11);
        }
        return vectorList;
    },
    addFeature: function(layer, coordinates) {
        const ptProjection = getProjection('EPSG:3763');
        var pt = new Point(coordinates);
        var feat = new Feature({
            geometry: pt,
            //projection: 'EPSG:3763'
            //projection: 'EPSG:4258'
            projection: ptProjection
        });
        feat.setStyle(this.iconDefaultStyle);

        layer.getSource().addFeature(feat);

    }                              
};                                   
export default olMap; // export the object