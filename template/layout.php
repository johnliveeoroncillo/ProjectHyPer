<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <base href="<?=BASE_URL;?>/" />
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
    <script src="assets/js/script.js"></script>
    <script>
          const modalGet = '<?=(!empty($_GET['modal']) ? $_GET['modal'] : '');?>';
          const modalUrl = '<?=(!empty($_GET['url']) ? $_GET['url'] : '');?>';
          if (modalGet !== '') setTimeout(() => { showModal(modalGet, modalUrl) }, 300);
          function showModal(modal = '', url = '') {
              $('#'+modal).attr('style', 'display: grid !important;');
              $('#'+modal).find('#container').load(url, function() {
                  removeQs();
              })
          }
          function hideModal(modal = '') {
              $('#'+modal).removeAttr('style');
          }
          function addQs(key, value) {
            if ('URLSearchParams' in window) {
                const url = new URL(window.location)
                url.searchParams.set(key, value);
                history.pushState(null, '', url);
            }
          }
          function removeQs(key) {
              if (key && 'URLSearchParams' in window) {
                  const url = new URL(window.location)
                  url.searchParams.delete(key);
                  history.pushState(null, '', url);
              }
          }

          $(document).ready(function() {
              $('select').each(function(i ,obj) {
                  if ($(obj).attr('value')) {
                      $(obj).val($(obj).attr('value'));
                  }
              });

              $('.dropdown').on('click', function() {
                  if ($(this).parent().find('ul').is(':hidden')) {
                    $(this).parent().find('ul').show();
                  } else $(this).parent().find('ul').hide();
              });

              const url = window.location;
              const fullUrl = url.href;
              const path = url.pathname;
              const isDevelop = fullUrl.includes('localhost');
              const split = path.split('/');
              const hasFolder = '<?=FOLDER;?>';
              split.shift(); // remove empty
              if (hasFolder) {
                split.shift(); // remove folder
              }
              let newPath = split.join('/');
              $('a[href]:not(.no-active)').each((element, obj) => {
                  const origPath = path;
                  newPath = newPath.split('/');
                  newPath = newPath.shift();
                  if ($(obj).hasClass('strict-active') && origPath !== '' && '/'+$(obj).attr('href') === origPath) {
                      console.log(newPath);
                      $(obj).addClass('link-active');
                  } else if (!$(obj).hasClass('strict-active') && newPath !== '' && $(obj).attr('href').includes(newPath)) {
                      $(obj).addClass('link-active');
                  }
              });
          });
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
