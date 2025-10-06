<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Consulta de Rezagos</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Favicon (opcional si existe) -->
  <link rel="icon" href="{{ asset('solar/img/favicon.ico') }}">

  <!-- Fuentes / Iconos -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

  <!-- Bootstrap -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">

  <style>
    :root{
      --overlay: rgba(0, 16, 40, .55);
      --card: rgba(9, 22, 43, 0.88);
      --radius: 18px;
    }
    html, body {height: 100%;}
    body{
      font-family: 'Montserrat', sans-serif;
      color: #e9eef7;
      background: #0b1b2b;
      overflow-x: hidden;
    }
    /* HERO de fondo */
    .hero{
      min-height: 100vh;
      position: relative;
      background: url('{{ asset('solar/img/carousel-1.jpg') }}') center/cover no-repeat fixed;
      display: grid;
      place-items: center;
    }
    .hero::before{
      content:"";
      position:absolute; inset:0;
      background: linear-gradient(180deg, var(--overlay), rgba(0,0,0,.65));
    }

    /* Contenedor centrado */
    .center-wrap{
      position: relative;
      width: 100%;
      max-width: 880px;
      margin: 0 auto;
      padding: 24px;
    }

    /* Tarjeta buscador */
    .search-card{
      background: var(--card);
      border-radius: var(--radius);
      box-shadow: 0 10px 30px rgba(0,0,0,.35);
      padding: 22px;
      backdrop-filter: blur(4px);
    }
    .title{
      font-weight: 600;
      letter-spacing: .4px;
      margin-bottom: 14px;
    }
    .input-group-lg .form-control{
      height: 58px;
      border: none;
      border-radius: var(--radius) 0 0 var(--radius) !important;
      padding-left: 20px;
    }
    .btn-find{
      height: 58px;
      border-radius: 0 var(--radius) var(--radius) 0 !important;
      border: none;
      font-weight: 600;
    }
    .hint{
      opacity: .9; font-size: .85rem;
    }

    /* Resultados */
    .result-card{
      margin-top: 18px;
      background: var(--card);
      border-radius: var(--radius);
      box-shadow: 0 10px 30px rgba(0,0,0,.35);
      padding: 18px 20px;
    }
    .result-item{
      display: flex;
      align-items: flex-start;
    }
    .result-icon{
      width: 52px; height: 52px;
      display: grid; place-items: center;
      border-radius: 12px; background: rgba(0,123,255,.15);
      margin-right: 14px;
    }
    .kv{
      margin: 2px 0;
    }
    .kv .k{
      color: #8ea8c9; min-width: 110px; display: inline-block;
    }

    .empty{
      display:none;
    }

    @media (max-width: 576px){
      .center-wrap{ padding: 16px; }
      .kv .k{ min-width: 96px; }
    }
  </style>
</head>
<body>

  <section class="hero">
    <div class="center-wrap">
      <!-- BUSCADOR -->
      <div class="search-card">
        <h5 class="title mb-2"><i class="fas fa-search mr-2"></i>Busca aquí tu código</h5>
        <div class="input-group input-group-lg">
          <input id="qPaquete" type="text" class="form-control"
                 placeholder="Ej.: EN000123LP o RP85413403" aria-label="Buscar por código">
          <div class="input-group-append">
            <button id="btnBuscarPaquete" class="btn btn-primary btn-find px-4">Buscar</button>
          </div>
        </div>
        <small class="hint d-block mt-2">La búsqueda se realiza <strong>solo por código</strong>.</small>
      </div>

      <!-- RESULTADOS -->
      <div id="resultadoPaquetes" class="result-card empty">
        <div id="listaPaquetes"></div>
      </div>

      <!-- SIN RESULTADOS -->
      <div id="sinResultados" class="result-card empty">
        <div class="alert alert-warning mb-0">
          No se encontraron registros para ese código.
        </div>
      </div>
    </div>
  </section>

  <!-- JS -->
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>

  <script>
  (function(){
  function itemTemplate(p){
    // Solo Código y Estado (y Destinatario pequeño si existe)
    const dest  = p.destinatario ? ` <small class="text-muted">• ${p.destinatario}</small>` : '';
    return `
      <div class="result-item py-2 border-bottom border-secondary">
        <div class="result-icon">
          <i class="fas fa-box text-primary"></i>
        </div>
        <div class="flex-fill">
          <div class="kv"><span class="k">Código:</span> <strong>${p.codigo ?? '-'}</strong>${dest}</div>
          <div class="kv"><span class="k">Estado:</span> <span>${p.estado ?? '-'}</span></div>
        </div>
      </div>`;
  }
    function show(el){ el.classList.remove('empty'); }
    function hide(el){ el.classList.add('empty'); }

    function pintar(items){
      const wrap = document.getElementById('resultadoPaquetes');
      const list = document.getElementById('listaPaquetes');
      const empty = document.getElementById('sinResultados');

      if (!items || items.length === 0){
        hide(wrap); show(empty);
        list.innerHTML = '';
        return;
      }
      hide(empty); show(wrap);
      list.innerHTML = items.map(itemTemplate).join('');
    }

    let t = null;
    function buscar(q){
      const res = document.getElementById('resultadoPaquetes');
      const empty = document.getElementById('sinResultados');
      if (!q || q.trim() === ''){
        hide(res); hide(empty);
        document.getElementById('listaPaquetes').innerHTML = '';
        return;
      }
      $.get(`{{ route('rezagos.buscar') }}`, { q })
        .done(resp => pintar(resp.data || []))
        .fail(() => {
          hide(res);
          show(empty);
          $(empty).find('.alert').text('Ocurrió un error al buscar. Intenta nuevamente.');
        });
    }

    $('#btnBuscarPaquete').on('click', function(){
      buscar($('#qPaquete').val());
    });

    $('#qPaquete').on('keydown', function(e){
      if (e.key === 'Enter'){
        e.preventDefault();
        buscar(this.value);
      }
    });

    // Debounce para tipeo
    $('#qPaquete').on('input', function(){
      clearTimeout(t);
      const q = this.value;
      t = setTimeout(function(){ buscar(q); }, 350);
    });
  })();
  </script>
</body>
</html>
