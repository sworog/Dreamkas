Testo
=====

Server for testing pages in any browser

To start server type in console:

    node testo_server

To run tests type in console:

    node testo_run

Directives in testo_config.js
-----------------------------

**uri** - uri of page that contains your tests.

**timeout** - timeout for all tests

**browsers** - paths to your browsers


How to commit tests result
--------------------------

Put this code on your tests page:

    <script src="testo_library.js">
        window.$testo_slave= $testo_slave
    </script>

Than call `$test_slave.done( Boolean( passed ) )` when tests are completed.
