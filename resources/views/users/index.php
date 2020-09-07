{{ @master('layouts.master') }}
{{ @section('title', 'List of users')}}

{{ @section('meta') }}
{{ @endsection() }}

{{ @section('content') }}

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
            <table class="table">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>name</th>
                        <th>show</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users['data'] as $user)
                    <tr>
                        <td scope="row">{{ $user['user_id'] }}</td>
                        <td>{{ $user['full_name'] }}</td>
                        <td><a href="/users/{{ $user['user_id'] }}/edit">edit</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
{{ endsection() }}