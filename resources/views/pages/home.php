{{ @master('layouts.master') }}
{{ @section('title', 'Clean Blog - Home') }}
{{ @section('meta') }}
<meta property="og:type" content="website" />
<meta property="og:url" content="https://stackoverflow.com/questions/8221022/css-not-loading-after-redirect-with-htaccess-rewrite-rule" />
<meta property="og:site_name" content="Stack Overflow" />
<meta property="og:image" itemprop="image primaryImageOfPage" content="https://cdn.sstatic.net/Sites/stackoverflow/img/apple-touch-icon@2.png?v=73d79a89bded" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:domain" content="stackoverflow.com" />
<meta name="twitter:title" property="og:title" itemprop="title name" content="CSS not loading after redirect with htaccess rewrite rule" />
<meta name="twitter:description" property="og:description" itemprop="description" content="I have the following Short-hand for a user profile url" />
{{ @endsection(); }}

{{ @section('content') }}
<!-- Page Header -->
<header class="masthead" style="background-image: url({{ assets('assets/template/img/home-bg.jpg') }})">
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
<!-- Main Content -->
<div class="container">
  <div class="row">
    <div class="col-lg-8 col-md-10 mx-auto">
      <div class="post-preview">
        <a href="post.html">
          <h2 class="post-title">
            Man must explore, and this is exploration at its greatest
          </h2>
          <h3 class="post-subtitle">
            Problems look mighty small from 150 miles up
          </h3>
        </a>
        <p class="post-meta">Posted by
          <a href="#">Start Bootstrap</a>
          on September 24, 2018</p>
      </div>
      <hr>
      <div class="post-preview">
        <a href="post.html">
          <h2 class="post-title">
            I believe every human has a finite number of heartbeats. I don't intend to waste any of mine.
          </h2>
        </a>
        <p class="post-meta">Posted by
          <a href="#">Start Bootstrap</a>
          on September 18, 2018</p>
      </div>
      <hr>
      <div class="post-preview">
        <a href="post.html">
          <h2 class="post-title">
            Science has not yet mastered prophecy
          </h2>
          <h3 class="post-subtitle">
            We predict too much for the next year and yet far too little for the next ten.
          </h3>
        </a>
        <p class="post-meta">Posted by
          <a href="#">Start Bootstrap</a>
          on August 24, 2018</p>
      </div>
      <hr>
      <div class="post-preview">
        <a href="post.html">
          <h2 class="post-title">
            Failure is not an option
          </h2>
          <h3 class="post-subtitle">
            Many say exploration is part of our destiny, but it’s actually our duty to future generations.
          </h3>
        </a>
        <p class="post-meta">Posted by
          <a href="#">Start Bootstrap</a>
          on July 8, 2018</p>
      </div>
      <hr>
      <!-- Pager -->
      <div class="clearfix">
        <a class="btn btn-primary float-right" href="#">Older Posts &rarr;</a>
      </div>
    </div>
  </div>
</div>
{{ @endsection() }}