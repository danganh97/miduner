{{ @master('layouts.master') }}
{{ @section('title', 'Clean Blog - About') }}
{{ @section('meta')}}
<meta property="og:type" content="website" />
<meta property="og:url"
  content="https://stackoverflow.com/questions/8221022/css-not-loading-after-redirect-with-htaccess-rewrite-rule" />
<meta property="og:site_name" content="Stack Overflow" />
<meta property="og:image" itemprop="image primaryImageOfPage"
  content="https://cdn.sstatic.net/Sites/stackoverflow/img/apple-touch-icon@2.png?v=73d79a89bded" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:domain" content="stackoverflow.com" />
<meta name="twitter:title" property="og:title" itemprop="title name"
  content="CSS not loading after redirect with htaccess rewrite rule" />
<meta name="twitter:description" property="og:description" itemprop="description"
  content="I have the following Short-hand for a user profile url" />
{{ @endsection(); }}

{{ @section('content') }}
<!-- Page Header -->
<header class="masthead" style="background-image: url({{ assets('assets/template/img/home-bg.jpg') }})">
  <div class="overlay"></div>
  <div class="container">
    <div class="row">
      <div class="col-lg-8 col-md-10 mx-auto">
        <div class="site-heading">
          <h1>About</h1>
          <span class="subheading">A Blog Theme by Start Bootstrap</span>
        </div>
      </div>
    </div>
  </div>
</header>
<!-- Main Content -->
<div class="container">
  <div class="row">
    <div class="col-lg-8 col-md-10 mx-auto">
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Saepe nostrum ullam eveniet pariatur voluptates odit,
        fuga atque ea nobis sit soluta odio, adipisci quas excepturi maxime quae totam ducimus consectetur?</p>
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eius praesentium recusandae illo eaque architecto
        error, repellendus iusto reprehenderit, doloribus, minus sunt. Numquam at quae voluptatum in officia voluptas
        voluptatibus, minus!</p>
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aut consequuntur magnam, excepturi aliquid ex itaque
        esse est vero natus quae optio aperiam soluta voluptatibus corporis atque iste neque sit tempora!</p>
    </div>
  </div>
</div>

{{ @endsection() }}