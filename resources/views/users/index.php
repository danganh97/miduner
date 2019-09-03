<?php $this->setVars('title', 'Clean Blog - Home') ?>
<?php $this->setVars('script', '') ?>
<?php $this->setVars('meta', '
<meta property="og:type" content= "website" />
<meta property="og:url" content="https://stackoverflow.com/questions/8221022/css-not-loading-after-redirect-with-htaccess-rewrite-rule"/>
<meta property="og:site_name" content="Stack Overflow" />
<meta property="og:image" itemprop="image primaryImageOfPage" content="https://cdn.sstatic.net/Sites/stackoverflow/img/apple-touch-icon@2.png?v=73d79a89bded" />
<meta name="twitter:card" content="summary"/>
<meta name="twitter:domain" content="stackoverflow.com"/>
<meta name="twitter:title" property="og:title" itemprop="title name" content="CSS not loading after redirect with htaccess rewrite rule" />
<meta name="twitter:description" property="og:description" itemprop="description" content="I have the following Short-hand for a user profile url" />
') ?>
<header class="masthead" style="background-image: url('assets/template/img/home-bg.jpg')">
      <div class="overlay"></div>
      <div class="container">
        <div class="row">
          <div class="col-lg-8 col-md-10 mx-auto">
            <div class="site-heading">
              <h1>Clean Blog</h1>
              <span class="subheading">A Blog Theme by Start Bootstrap</span>
            </div>
          </div>
        </div>
      </div>
</header>
<div class="container">
<table class="table">
    <thead>
        <tr>
            <th>id</th>
            <th>name</th>
            <th>show</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($users as $user){ ?>
        <tr>
            <td scope="row"><?php echo $user->user_id ?></td>
            <td><?php echo $user->full_name ?></td>
            <td><a href="/users/<?php echo $user->user_id ?>/edit">edit</a></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
</div>