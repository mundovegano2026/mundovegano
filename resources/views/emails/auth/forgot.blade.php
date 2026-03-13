<?php 
function loadFile($url) {
  $ch = curl_init();

  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_URL, $url);

  $data = curl_exec($ch);
  curl_close($ch);

  return $data;
}

$logoData = loadFile(URL::to('/') . '/fonts/logo.png');
?>
<div style="text-align: center; width: 100%; background: green">
    <img src="data:image/png;base64,{{base64_encode($logoData)}}" alt="" style="left: 0; right: 0">
</div>


<h1>Olá, {{ $name }}!</h1>
<p>Clica <a target="_blank" href="<?php echo URL::to('/') . "/recoverpwd?confirmToken=" . $confirmToken; ?>">aqui</a> para gerar nova palavra-passe para a tua conta no Mundo Vegano.</p>

<div style="text-align: center; margin-top: 100px; padding-bottom: 5px; padding-top: 5px; width: 100%; background: green; color: white">
    <p style="width: 100%; text-align: center">Mundo Vegano © 2021</p>
    <p style="width: 100%; text-align: center">Se tens alguma questão podes contatar-nos <a href="{{ URL::to('/contacto') }}" target="_blank">aqui</a></p>
</div>