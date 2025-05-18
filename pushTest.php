<?
$file = <<<'FILE'
<!DOCTYPE html>
<html>
  <head>
    <title>Coordinates boilerplate example</title>
    <style>
      body, html{
        background: #333;
        margin: 0;
        min-height: 100vh;
        overflow: hidden;
      }
    </style>
  </head>
  <body>
    <script type="module">
    
      import * as Coordinates from
      "https://cantelope.mooo.com/coordinates.js"
    
      var refTexture = 'https://srmcgann.github.io/Coordinates/resources/ultracloudsbase.jpg'
      
      var rendererOptions = {
        ambientLight: .5,
        fov: 1500
      }
      var renderer = await Coordinates.Renderer(rendererOptions)
      
      renderer.z = 16
      
      Coordinates.AnimationLoop(renderer, 'Draw')

      var shaderOptions = [
        { uniform: {
          type: 'phong',
          value: .25
        } }
      ]
      var shader = await Coordinates.BasicShader(renderer, shaderOptions)


      var shaderOptions = [
        { lighting: {type: 'ambientLight', value: .5}},
        { uniform: {
          type: 'phong',
          value: 0
        } },
      ]
      var backgroundShader = await Coordinates.BasicShader(renderer, shaderOptions)


      const downloadCustomShape = geo => {
      
        var vertices = []
        var normals = []
        var normalVecs = []
        var uvs = []
        
        for(var i = 0; i< geo.vertices.length; i++)
          vertices.push(Math.round(geo.vertices[i]*1e3)/1e3)
      
        for(var i = 0; i< geo.uvs.length; i++)
          uvs.push(Math.round(geo.uvs[i]*1e3)/1e3)
      
        for(var i = 0; i< geo.normals.length; i++)
          normals.push(Math.round(geo.normals[i]*1e3)/1e3)
      
        for(var i = 0; i< geo.normalVecs.length; i++)
          normalVecs.push(Math.round(geo.normalVecs[i]*1e3)/1e3)
      
        var object = { vertices, uvs, normals, normalVecs}
        var link = document.createElement('a')
        var str = 'data:text/json;charset=utf-8,' + encodeURIComponent(JSON.stringify(object))
        link.setAttribute('href', str)
        link.setAttribute('download', geo.name)
        link.click()
      }

      var shapes = []
      for(var j = 0; j<4; j++){
        var i = ([61, 115, 198, 300])[j]-1
        var ct = (''+(i+1)).padStart(4, '0')
        //var url = `https://srmcgann.github.io/objs/alienDog/eyes/frame${ct}.obj`
        var url = `https://srmcgann.github.io/objs/alienDog/body/frame${ct}.obj`
        var geoOptions = {
          shapeType: 'obj',
          //name: `eyess${ct}.json`,
          name: `bodyy${ct}.json`,
          url,
          //y: -2,
          scaleX: .75,
          scaleY: .75,
          scaleZ: .75,
          colorMix: 0,
          map: 'https://srmcgann.github.io/objs/alienDog/textures/eye.jpg',
          //exportShape: 
        }
        await Coordinates.LoadGeometry(renderer, geoOptions).then(async (geometry) => {
          shapes.push(geometry)
          downloadCustomShape(geometry)
          await shader.ConnectGeometry(geometry)
        })  
      }
      
      var geoOptions = {
        shapeType: 'dodecahedron',
        name: 'background',
        subs: 2,
        size: 1e4,
        map: refTexture,
        colorMix: 0,
      }
      await Coordinates.LoadGeometry(renderer, geoOptions).then(async (geometry) => {
        shapes.push(geometry)
        await backgroundShader.ConnectGeometry(geometry)
      })  

      
      window.Draw = () => {
        switch(shape.name){
          case 'background':
          break
          default:
            shape.yaw += .01
            //shape.pitch += .005
          break
        }
        renderer.Draw(shape)
      }
      
    </script>
  </body>
</html>

FILE;
file_put_contents('../../../cantelope.mooo.com/test.html', $file);
?>