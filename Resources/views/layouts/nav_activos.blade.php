<section class="no-print">
    <nav class="navbar navbar-default bg-white m-4">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ route('activos_dashboard.index') }}"><i
                            class="fa fas fa-cha"></i> {{__('activos::lang.activos')}}</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">


                  {{--  <li @if(request()->segment(2) == 'financial-accounts') class="active" @endif>
                        <a href=" {{ route('financial-accounts.index') }}">Cuentas financieras</a>
                    </li>--}}
                </ul>
            </div>
        </div>
    </nav>
</section>