<div>
  <h1>Account</h1>

  <p>Welcome to your account page.</p>
  @foreach ($movies as $item)
    @php
      $post = get_post($item);
  @endphp
    {{ $post->post_title }}
  @endforeach
</div>
