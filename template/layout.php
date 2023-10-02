<?php
    $global_settings = $db->get_where_row('settings');
    $vol = (!empty($global_settings) ? $global_settings['volume'] : 0);
;?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <base href="<?=BASE_URL;?>" />
    <title><?=APP_NAME;?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="assets/img/logo.png" rel="icon" />
    <link href="assets/css/main-output.css?v=<?=microtime(true);?>" rel="stylesheet">
    <style>
      html, body {
        width: 100%;
      }

      .modal {
          display: none !important;
      }
    </style>
    
          
    <script src="assets/js/jquery-3.6.1.min.js"></script>
    <script src="assets/js/html2canvas.hertzen.com_dist_html2canvas.min.js"></script>
    <script src="assets/js/js.pusher.com_8.2.0_pusher.min.js"></script>
    <script src="assets/js/websockets.js"></script>
<script>
          const modalGet = '<?=(!empty($_GET['modal']) ? $_GET['modal'] : '');?>';
          const modalUrl = '<?=(!empty($_GET['url']) ? $_GET['url'] : '');?>';
          if (modalGet !== '') setTimeout(() => { showModal(modalGet, modalUrl) }, 300);
          function showModal(modal = '', url = '') {
            console.log(url);
              $('#'+modal).attr('style', 'display: grid !important;');
              $('#'+modal).find('#container').load(url, function() {
                  removeQs();
              })
          }
          function hideModal(modal = '') {
              $('#'+modal).removeAttr('style');
          }
          function deleteData(table, id) {
              window.location.href = "api/admin/delete-data?table="+table+"&id=" + id;
          }
          function removeQs() {
              // const params = new URLSearchParams(window.location.search);
              //params.delete("test");
              // history.pushState({pageID: 'about'}, 'About', '/about');

              var uri = window.location.toString();
              if (uri.indexOf("?") > 0) {
                  var clean_uri = uri.substring(0, uri.indexOf("?"));
                  window.history.replaceState({}, document.title, clean_uri);
              }
          }
    </script>
  </head>
  <body class="flex flex-col" id="bg">
      <?=(!empty($data['header']) ? $data['header'] : '');?>
      <div class="flex-1 relative overflow-hidden content">
            <div class="absolute top-0 left-0 w-full h-full overflow-auto py-4" id="container">
                <?=(!empty($data['content']) ? $data['content'] : '');?>
            </div>
      </div>
      <!-- <?=(!empty($data['footer']) ? $data['footer'] : '');?> -->


      <audio id="sound" autostart="0" style="display: none;">
         <source src="assets/effect.mp3" type="audio/mpeg">
      </audio>
      <button id="play" class="hidden">Play</button>
      <button id="play1" class="text-black" style="display: none;">Play</button>
      <?php
        $modal = glob('./**/*-modal.php');
        if (!empty($modal)) {
            foreach ($modal as $file) {
                include($file);
            }
        }
      ;?>
      <script src="assets/js/speech.js"></script>
      <script>
            speech.volume = <?=$vol;?>;
      </script>
      <script>
        $(document).ready(function() {
            $('.dropdown').on('click', function() {
                if ($(this).find('ul').is(':hidden')) {
                  $(this).find('ul').show();
                } else $(this).find('ul').hide();
            });

            $('select[name][value]').each(function(e, obj) {
                const value = $(obj).attr('value');
                $(obj).val(value);
            });

            $(window).on('resize', function() {
                checkWidth();
            });

            function checkWidth() {
                const width = $(window).width();
                if (width < 1024) {
                    $('#dynamic-header').addClass('hidden');
                    $('#dynamic-header').removeClass('flex');
                } else {
                    $('#dynamic-header').removeClass('hidden');
                    $('#dynamic-header').addClass('flex');
                }
            }

            $('#open').on('click', function() {
                $('#dynamic-header').removeClass('hidden');
                $('#dynamic-header').addClass('flex');
            });

            $('#close').on('click', function() {
                $('#dynamic-header').addClass('hidden');
                $('#dynamic-header').removeClass('flex');
            });

            checkWidth();

            const url = window.location;
            const fullUrl = url.href;
            const path = url.pathname;
            const isDevelop = fullUrl.includes('localhost');
            const split = path.split('/');
            split.shift(); // remove empty
            split.shift(); // remove folder
            const newPath = split.join('/');
            $('a[href]').each((element, obj) => {
                if (newPath === $(obj).attr('href')) {
                    $(obj).addClass('link-active');
                }
            });
            
            $('#play').on('click', function() {
                var audio = document.getElementById('sound');
                audio.play();
            });

            var audio = document.getElementById('sound');
            audio.volume = <?=$vol;?>/100;
            console.log(audio.volume);

            $('#second-monitor').on('click', function() {
                const w = $(window).width();
                const h = $(window).height();
                console.log(w, h);
                var NWin = window.open('<?=BASE_URL;?>second-monitor', '', 'height=' + h + 'px','width=' + w + 'px');
                if (window.focus) {
                    NWin.focus();
                }
                return false;
            });
        });
      </script>
  </body>
</html>
