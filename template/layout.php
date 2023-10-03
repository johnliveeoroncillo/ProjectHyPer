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

    <?php
      if (IS_DEVELOP) { ;?>
        <script src="assets/js/script.raw.js"></script>
    <?php } else { ;?>
        <script src="assets/js/script.js"></script>
    <?php } ;?>
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
      <?=(!empty($data['content']) ? $data['content'] : '');?>
      <!-- <?=(!empty($data['footer']) ? $data['footer'] : '');?> -->

      <?php
        $modal = glob('./**/*-modal.php');
        if (!empty($modal)) {
            foreach ($modal as $file) {
                include($file);
            }
        }
      ;?>
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

            const url = window.location;
            const fullUrl = url.href;
            const path = url.pathname;
            const isDevelop = fullUrl.includes('localhost');
            const split = path.split('/');
            split.shift(); // remove empty
            split.shift(); // remove folder
            let newPath = split.join('/');
            $('a[href]:not(.no-active)').each((element, obj) => {
              console.log(newPath);
                newPath = newPath.split('/');
                newPath = newPath.shift();
                if (newPath !== '' && $(obj).attr('href').includes(newPath)) {
                    $(obj).addClass('link-active');
                }
            });
        });
      </script>
  </body>
</html>
