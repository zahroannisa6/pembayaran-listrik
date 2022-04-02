<footer class="border-top border-bottom py-3 py-md-0">
  <div class="container d-flex justify-content-center align-items-center">
    <div class="row">
      <div class="col-12 col-md-auto help-link">
        <img src="{{ asset('assets/img/electricity.png') }}" width="140" height="83" alt="Logo">
      </div>
      <div class="col-12 col-md-auto">
        <ul class="list-unstyled">
          <li class="list-item font-weight-bold">Bantuan</li>
          <li class="list-item">Kebijakan Privasi</li>
          <li class="list-item"><a href="{{route('faq')}}" class="text-decoration-none text-dark">FAQ</a></li>
          <li class="list-item"><a href="{{route('how_to_pay')}}" class="text-decoration-none text-dark">Cara Bayar</a></li>
          <li class="list-item"><a href="/docs" class="text-decoration-none text-dark">Developer Docs</a></li>
        </ul>
      </div>
      <div class="col-12 col-md-auto">
        <ul class="list-unstyled">
          <li class="list-item font-weight-bold">Sosial</li>
          <li class="list-item"><a href="https://www.instagram.com/zahroas_/" class="text-dark text-decoration-none">Instagram</a></li>
          <li class="list-item"><a href="https://wa.me/+6285975402938?text=Halo Admin" class="text-dark text-decoration-none">Whatsapp</a></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="copyright d-flex justify-content-center align-items-center p-3">
    <span>© {{date('Y')}} All Right Reserved By Zahrotun Annisa Sholihah</span>
  </div>
</footer>