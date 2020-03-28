<?php
header("Access-Control-Allow-Origin：http://34.80.195.241");
$info = file_get_contents('http://34.80.195.241/corona/2.php');
$info = json_decode($info, true);
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>COVID-19 CORONAVIRUS PANDEMIC</title>
        <link rel="Shortcut Icon" href="../favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="../bootstrap/bootstrap-table-master/dist/foundation.min.css" integrity="sha256-xpOKVlYXzQ3P03j397+jWFZLMBXLES3IiryeClgU5og= sha384-gP4DhqyoT9b1vaikoHi9XQ8If7UNLO73JFOOlQV1RATrA7D0O7TjJZifac6NwPps sha512-AKwIib1E+xDeXe0tCgbc9uSvPwVYl6Awj7xl0FoaPFostZHOuDQ1abnDNCYtxL/HWEnVOMrFyf91TDgLPi9pNg==" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
        <link rel="stylesheet" href="../bootstrap/bootstrap-table-master/dist/themes/foundation/bootstrap-table-foundation.min.css">
<!--		   <link rel="stylesheet" href="../bootstrap/bootstrap-table-master/dist/bootstrap-table.min.css">-->
<!--        <link rel="stylesheet" href="../bootstrap/bootstrap-table-master/dist/themes/semantic/bootstrap-table-semantic.min.css">-->
<!--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css">-->
        <style>
            .title{font-size: 26px;}
            .t1{padding-left: 65px;}
        </style>
    </head>
    <body style="padding: 20px 20px;">
        <h2 style="text-align: center;">COVID-19 CORONAVIRUS PANDEMIC</h2>
        <div class="title">
            <span class="t1">Coronavirus Cases：<?php echo empty($info['cases']) ? 0 : $info['cases'];?></span>
            <span class="t1">Deaths：<?php echo empty($info['deaths']) ? 0 : $info['deaths'];?></span>
            <span class="t1">Recovered：<?php echo empty($info['recovered']) ? 0 : $info['recovered'];?></span>
        </div>
        <div class='title t1'><span>Last Updated Time: <?php echo $info['last_updated'];?></span></div>
        <table
              id="table"
              data-show-refresh="true"
              data-auto-refresh="true"
              data-pagination="false"
              data-url="http://34.80.195.241/corona/1.php"
              data-side-pagination="server"
              data-show-print="true"
              data-header-style="headerStyle"
              data-search="false">
        <thead>
        <tr>
            <th data-field="country" data-sortable="true">城市</th>
              <th data-field="total_cases" data-sortable="true">确诊数</th>
              <th data-field="new_cases" data-sortable="true" data-cell-style="casesStyle">新增</th>
              <th data-field="total_deaths" data-sortable="true">累计死亡</th>
              <th data-field="new_deaths" data-sortable="true" data-cell-style="deathsStyle">新增死亡</th>
              <th data-field="total_recovered" data-sortable="true">治愈数量</th>
              <th data-field="active_cases" data-sortable="true">现存确诊</th>
              <th data-field="serious_critical" data-sortable="true">重症病例</th>
              <th data-field="tot_cases_1m" data-sortable="true">每百万确诊数</th>
              <th data-field="tot_deaths_1m" data-sortable="true">每百万死亡数</th>
              <th data-field="ost_case" data-sortable="true">首例时间</th>
          </tr>
          </thead>
        </table>
        <script src="../bootstrap/jquery-3.3.1.min.js"></script>
        <script src="../bootstrap/bootstrap-table-master/dist/bootstrap-table.min.js"></script>
        <script src="../bootstrap/bootstrap-table-master/dist/foundation.min.js" integrity="sha256-/PFxCnsMh+nTuM0k3VJCRch1gwnCfKjaP8rJNq5SoBg= sha384-9ksAFjQjZnpqt6VtpjMjlp2S0qrGbcwF/rvrLUg2vciMhwc1UJJeAAOLuJ96w+Nj sha512-UMSn6RHqqJeJcIfV1eS2tPKCjzaHkU/KqgAnQ7Nzn0mLicFxaVhm9vq7zG5+0LALt15j1ljlg8Fp9PT1VGNmDw==" crossorigin="anonymous"></script>
        <script src="../bootstrap/bootstrap-table-master/dist/extensions/auto-refresh/bootstrap-table-auto-refresh.min.js"></script>
<!--        <script src="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.js"></script>-->
        <script src="../bootstrap/bootstrap-table-master/dist/themes/foundation/bootstrap-table-foundation.js"></script>
<!--        <script src="https://unpkg.com/bootstrap-table@1.16.0/dist/themes/foundation/bootstrap-table-foundation.min.js"></script>-->
        <script src="../bootstrap/bootstrap-table-master/dist/extensions/print/bootstrap-table-print.min.js"></script>
<!--    <script src="../bootstrap/bootstrap-table-master/dist/themes/semantic/bootstrap-table-semantic.min.js"></script>-->
      <script>
          $(function() {
              $('#table').bootstrapTable()
          })
          
          function casesStyle(value, row, index) {
              if (value) {
                  return {
                      css: {
                          background: '#FFDC35'
                      }
                  }
              } else{
                  return {}
              }
          }

          function deathsStyle(value, row, index) {
              if (value) {
                  return {
                      css: {
                          background: '#FF0000'
                      }
                  }
              } else{
                  return {}
              }
          }

          function headerStyle(column) {
              return {
                  new_cases: {
                      css: {background: '#FFDC35'}
                  },
                  new_deaths: {
                      css: {background: '#FF0000'}
                  }
              }[column.field]
          }
          // window.ajaxOptions = {
          //   beforeSend: function (xhr) {
          //     xhr.setRequestHeader('Custom-Auth-Token', 'custom-auth-token')
          //   }
          // }
      </script>
  </body>
</html>










