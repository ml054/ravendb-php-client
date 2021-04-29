<?php











namespace Composer;

use Composer\Autoload\ClassLoader;
use Composer\Semver\VersionParser;






class InstalledVersions
{
private static $installed = array (
  'root' => 
  array (
    'pretty_version' => 'dev-master',
    'version' => 'dev-master',
    'aliases' => 
    array (
    ),
    'reference' => '5aa353d990c0d9542bc24d864ac20c85966ddb2b',
    'name' => 'initxlab/ravendb',
  ),
  'versions' => 
  array (
    'amphp/amp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '2.x-dev',
      ),
      'reference' => '7d4bbc6e0b47c6bb39b6cce1a4b5942e0c5125fb',
    ),
    'amphp/byte-stream' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '1.x-dev',
      ),
      'reference' => '7a64a9ad336fc5e1e70b1c1fc1e9618a7027332e',
    ),
    'analog/analog' => 
    array (
      'pretty_version' => '1.0.18-stable',
      'version' => '1.0.18.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd9b5db551ccd91d3ce41a7425da6672d038ee6b1',
    ),
    'brick/math' => 
    array (
      'pretty_version' => '0.9.2',
      'version' => '0.9.2.0',
      'aliases' => 
      array (
      ),
      'reference' => 'dff976c2f3487d42c1db75a3b180e2b9f0e72ce0',
    ),
    'composer/package-versions-deprecated' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '1.x-dev',
      ),
      'reference' => 'f921205948ab93bb19f86327c793a81edb62f236',
    ),
    'composer/semver' => 
    array (
      'pretty_version' => 'dev-main',
      'version' => 'dev-main',
      'aliases' => 
      array (
        0 => '3.x-dev',
      ),
      'reference' => 'dd61cb4efbd0cff1700b217faf24ce596af4fc4e',
    ),
    'composer/xdebug-handler' => 
    array (
      'pretty_version' => '2.0.0',
      'version' => '2.0.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '31d57697eb1971712a08031cfaff5a846d10bdf5',
    ),
    'dnoegel/php-xdg-base-dir' => 
    array (
      'pretty_version' => 'v0.1.1',
      'version' => '0.1.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '8f8a6e48c5ecb0f991c2fdcf5f154a47d85f9ffd',
    ),
    'doctrine/annotations' => 
    array (
      'pretty_version' => '1.13.x-dev',
      'version' => '1.13.9999999.9999999-dev',
      'aliases' => 
      array (
        0 => '9999999-dev',
      ),
      'reference' => 'c66f06b7c83e9a2a7523351a9d5a4b55f885e574',
    ),
    'doctrine/cache' => 
    array (
      'pretty_version' => '1.10.x-dev',
      'version' => '1.10.9999999.9999999-dev',
      'aliases' => 
      array (
        0 => '9999999-dev',
      ),
      'reference' => 'e8fc00bfecc87a5baab332f71ea2650b7afc4308',
    ),
    'doctrine/collections' => 
    array (
      'pretty_version' => '1.7.x-dev',
      'version' => '1.7.9999999.9999999-dev',
      'aliases' => 
      array (
      ),
      'reference' => '436a4eb4535f141c6c52a3099016cb22ceca05b5',
    ),
    'doctrine/common' => 
    array (
      'pretty_version' => '3.2.x-dev',
      'version' => '3.2.9999999.9999999-dev',
      'aliases' => 
      array (
      ),
      'reference' => 'a036d90c303f3163b5be8b8fde9b6755b2be4a3a',
    ),
    'doctrine/dbal' => 
    array (
      'pretty_version' => '2.13.x-dev',
      'version' => '2.13.9999999.9999999-dev',
      'aliases' => 
      array (
      ),
      'reference' => 'c800380457948e65bbd30ba92cc17cda108bf8c9',
    ),
    'doctrine/deprecations' => 
    array (
      'pretty_version' => 'v0.5.3',
      'version' => '0.5.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '9504165960a1f83cc1480e2be1dd0a0478561314',
    ),
    'doctrine/event-manager' => 
    array (
      'pretty_version' => '1.2.x-dev',
      'version' => '1.2.9999999.9999999-dev',
      'aliases' => 
      array (
      ),
      'reference' => 'eb044d4b27d552d4065fbc0548183e24815240a8',
    ),
    'doctrine/inflector' => 
    array (
      'pretty_version' => '2.1.x-dev',
      'version' => '2.1.9999999.9999999-dev',
      'aliases' => 
      array (
      ),
      'reference' => 'c6a0da4f0e06aa5cd83a2c1a4e449fae98c8bad7',
    ),
    'doctrine/instantiator' => 
    array (
      'pretty_version' => '1.5.x-dev',
      'version' => '1.5.9999999.9999999-dev',
      'aliases' => 
      array (
      ),
      'reference' => '6410c4b8352cb64218641457cef64997e6b784fb',
    ),
    'doctrine/lexer' => 
    array (
      'pretty_version' => '1.3.x-dev',
      'version' => '1.3.9999999.9999999-dev',
      'aliases' => 
      array (
      ),
      'reference' => '59bfb3b9be04237be4cd1afea9bbb58794c25ce8',
    ),
    'doctrine/orm' => 
    array (
      'pretty_version' => '2.9.x-dev',
      'version' => '2.9.9999999.9999999-dev',
      'aliases' => 
      array (
      ),
      'reference' => 'ddfee26f80930b672c45bc6d95f21ca4880438ae',
    ),
    'doctrine/persistence' => 
    array (
      'pretty_version' => '2.1.x-dev',
      'version' => '2.1.9999999.9999999-dev',
      'aliases' => 
      array (
        0 => '9999999-dev',
      ),
      'reference' => 'fb399fefb5570c27c8b15c69c797b0ee559e1fe8',
    ),
    'felixfbecker/advanced-json-rpc' => 
    array (
      'pretty_version' => 'v3.2.0',
      'version' => '3.2.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '06f0b06043c7438959dbdeed8bb3f699a19be22e',
    ),
    'felixfbecker/language-server-protocol' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '1.x-dev',
      ),
      'reference' => '9d846d1f5cf101deee7a61c8ba7caa0a975cd730',
    ),
    'initxlab/ravendb' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
      ),
      'reference' => '5aa353d990c0d9542bc24d864ac20c85966ddb2b',
    ),
    'myclabs/deep-copy' => 
    array (
      'pretty_version' => '1.x-dev',
      'version' => '1.9999999.9999999.9999999-dev',
      'aliases' => 
      array (
        0 => '9999999-dev',
      ),
      'reference' => '776f831124e9c62e1a2c601ecc52e776d8bb7220',
      'replaced' => 
      array (
        0 => '9999999-dev',
        1 => '1.x-dev',
      ),
    ),
    'nahid/jsonq' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '9999999-dev',
      ),
      'reference' => '05227383e3d7256b226964234f33fc7bfa36f6c8',
    ),
    'nahid/qarray' => 
    array (
      'pretty_version' => 'v2.0.0',
      'version' => '2.0.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '09f5e7d702b938301abe23d888e791d9be655456',
    ),
    'netresearch/jsonmapper' => 
    array (
      'pretty_version' => 'v2.1.0',
      'version' => '2.1.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'e0f1e33a71587aca81be5cffbb9746510e1fe04e',
    ),
    'nikic/php-parser' => 
    array (
      'pretty_version' => 'v4.10.4',
      'version' => '4.10.4.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c6d052fc58cb876152f89f532b95a8d7907e7f0e',
    ),
    'ocramius/package-versions' => 
    array (
      'replaced' => 
      array (
        0 => '1.11.99',
      ),
    ),
    'openlss/lib-array2xml' => 
    array (
      'pretty_version' => '1.0.0',
      'version' => '1.0.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'a91f18a8dfc69ffabe5f9b068bc39bb202c81d90',
    ),
    'phar-io/manifest' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '2.0.x-dev',
      ),
      'reference' => '85265efd3af7ba3ca4b2a2c34dbfc5788dd29133',
    ),
    'phar-io/version' => 
    array (
      'pretty_version' => '3.1.0',
      'version' => '3.1.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'bae7c545bef187884426f042434e561ab1ddb182',
    ),
    'php-http/async-client-implementation' => 
    array (
      'provided' => 
      array (
        0 => '*',
      ),
    ),
    'php-http/client-implementation' => 
    array (
      'provided' => 
      array (
        0 => '*',
      ),
    ),
    'phpdocumentor/reflection-common' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '2.x-dev',
      ),
      'reference' => 'cf8df60735d98fd18070b7cab0019ba0831e219c',
    ),
    'phpdocumentor/reflection-docblock' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '5.x-dev',
      ),
      'reference' => 'f8d350d8514ff60b5993dd0121c62299480c989c',
    ),
    'phpdocumentor/type-resolver' => 
    array (
      'pretty_version' => '1.x-dev',
      'version' => '1.9999999.9999999.9999999-dev',
      'aliases' => 
      array (
        0 => '9999999-dev',
      ),
      'reference' => '6759f2268deb9f329812679e9dcb2d0083b2a30b',
    ),
    'phpspec/prophecy' => 
    array (
      'pretty_version' => '1.13.0',
      'version' => '1.13.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'be1996ed8adc35c3fd795488a653f4b518be70ea',
    ),
    'phpunit/php-code-coverage' => 
    array (
      'pretty_version' => '9.2.x-dev',
      'version' => '9.2.9999999.9999999-dev',
      'aliases' => 
      array (
      ),
      'reference' => 'f6293e1b30a2354e8428e004689671b83871edde',
    ),
    'phpunit/php-file-iterator' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '3.0.x-dev',
      ),
      'reference' => '8e1b28fa4315f9ab66b5a4ca4c93bd983361eefa',
    ),
    'phpunit/php-invoker' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '3.1.x-dev',
      ),
      'reference' => 'a46c3aae802b80c3442bd85006697666cd587441',
    ),
    'phpunit/php-text-template' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '2.0.x-dev',
      ),
      'reference' => '9e01d4179f9caf8028d0ae48cd84a6427a581793',
    ),
    'phpunit/php-timer' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '5.0.x-dev',
      ),
      'reference' => '60a62b284ad6770a1fdc7e07bff609be5233edfb',
    ),
    'phpunit/phpunit' => 
    array (
      'pretty_version' => '9.5.x-dev',
      'version' => '9.5.9999999.9999999-dev',
      'aliases' => 
      array (
      ),
      'reference' => 'd41e3f632163be390e38fbeac4b1c3b810e6ef2a',
    ),
    'psalm/psalm' => 
    array (
      'provided' => 
      array (
        0 => 'dev-master',
        1 => '4.x-dev',
      ),
    ),
    'psr/cache' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '3.0.x-dev',
      ),
      'reference' => '0a7c67d0d1c8167b342eb74339d6f961663826ce',
    ),
    'psr/container' => 
    array (
      'pretty_version' => '1.1.x-dev',
      'version' => '1.1.9999999.9999999-dev',
      'aliases' => 
      array (
      ),
      'reference' => '8622567409010282b7aeebe4bb841fe98b58dcaf',
    ),
    'psr/event-dispatcher' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '1.0.x-dev',
      ),
      'reference' => 'aa4f89e91c423b516ff226c50dc83f824011c253',
    ),
    'psr/event-dispatcher-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0',
      ),
    ),
    'psr/http-client-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0',
      ),
    ),
    'psr/log' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '1.1.x-dev',
      ),
      'reference' => 'a18c1e692e02b84abbafe4856c3cd7cc6903908c',
    ),
    'psr/log-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0',
      ),
    ),
    'ramsey/collection' => 
    array (
      'pretty_version' => '1.1.3',
      'version' => '1.1.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '28a5c4ab2f5111db6a60b2b4ec84057e0f43b9c1',
    ),
    'ramsey/uuid' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '4.x-dev',
      ),
      'reference' => 'e5b3699bbe04e18794a3b6e6e1e0443a9742e4ad',
    ),
    'rhumsaa/uuid' => 
    array (
      'replaced' => 
      array (
        0 => '4.x-dev',
        1 => 'dev-master',
      ),
    ),
    'sebastian/cli-parser' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '1.0.x-dev',
      ),
      'reference' => '32fe3bd93f89a92c20992150d9b4a0353e33cb86',
    ),
    'sebastian/code-unit' => 
    array (
      'pretty_version' => '1.0.8',
      'version' => '1.0.8.0',
      'aliases' => 
      array (
      ),
      'reference' => '1fc9f64c0927627ef78ba436c9b17d967e68e120',
    ),
    'sebastian/code-unit-reverse-lookup' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '2.0.x-dev',
      ),
      'reference' => '0c90ae4074246c4576e341a4eb8a880965b0bfa0',
    ),
    'sebastian/comparator' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '4.0.x-dev',
      ),
      'reference' => '7507dc3ba34a0a01a771d4004db63cf2f69ad5ae',
    ),
    'sebastian/complexity' => 
    array (
      'pretty_version' => '2.0.2',
      'version' => '2.0.2.0',
      'aliases' => 
      array (
      ),
      'reference' => '739b35e53379900cc9ac327b2147867b8b6efd88',
    ),
    'sebastian/diff' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '4.0.x-dev',
      ),
      'reference' => '7e1b9f252c029cfd8a1aef17489db44565181128',
    ),
    'sebastian/environment' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '5.1.x-dev',
      ),
      'reference' => 'e403442971a53d2aba4bfb8d51f3c1e67cd0cc8f',
    ),
    'sebastian/exporter' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '4.0.x-dev',
      ),
      'reference' => '24e3cf476cabd6d4b6f5a95ed2c06c2793b85429',
    ),
    'sebastian/global-state' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '5.0.x-dev',
      ),
      'reference' => '0f039e95ee9bd338306223844b449de04eb14cb4',
    ),
    'sebastian/lines-of-code' => 
    array (
      'pretty_version' => '1.0.3',
      'version' => '1.0.3.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c1c2e997aa3146983ed888ad08b15470a2e22ecc',
    ),
    'sebastian/object-enumerator' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '4.0.x-dev',
      ),
      'reference' => 'f2ae14774922039da37ba02727d006a00a5697d4',
    ),
    'sebastian/object-reflector' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '2.0.x-dev',
      ),
      'reference' => 'aa101bb19453d2b29a6ab66c2489cd07a84af4cd',
    ),
    'sebastian/recursion-context' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '4.0.x-dev',
      ),
      'reference' => '40cee94304292d3639df961c1b5d6eee0491eec3',
    ),
    'sebastian/resource-operations' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '3.0.x-dev',
      ),
      'reference' => '0f4443cb3a1d92ce809899753bc0d5d5a8dd19a8',
    ),
    'sebastian/type' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '2.3.x-dev',
      ),
      'reference' => '5101d5742b2ed2736e72111bcec63638648e0a5d',
    ),
    'sebastian/version' => 
    array (
      'pretty_version' => '3.0.2',
      'version' => '3.0.2.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c6c1022351a901512170118436c764e473f6de8c',
    ),
    'symfony/console' => 
    array (
      'pretty_version' => '5.x-dev',
      'version' => '5.9999999.9999999.9999999-dev',
      'aliases' => 
      array (
        0 => '9999999-dev',
      ),
      'reference' => '1d077bd682f7c0794d5f5b794b16e2b30febec6b',
    ),
    'symfony/deprecation-contracts' => 
    array (
      'pretty_version' => 'dev-main',
      'version' => 'dev-main',
      'aliases' => 
      array (
        0 => '2.4.x-dev',
      ),
      'reference' => '5f38c8804a9e97d23e0c8d63341088cd8a22d627',
    ),
    'symfony/event-dispatcher' => 
    array (
      'pretty_version' => '5.x-dev',
      'version' => '5.9999999.9999999.9999999-dev',
      'aliases' => 
      array (
        0 => '9999999-dev',
      ),
      'reference' => 'ff708cb53cbd8ff44d1f327d3169a4a75f0a7647',
    ),
    'symfony/event-dispatcher-contracts' => 
    array (
      'pretty_version' => 'dev-main',
      'version' => 'dev-main',
      'aliases' => 
      array (
        0 => '2.4.x-dev',
      ),
      'reference' => '69fee1ad2332a7cbab3aca13591953da9cdb7a11',
    ),
    'symfony/event-dispatcher-implementation' => 
    array (
      'provided' => 
      array (
        0 => '2.0',
      ),
    ),
    'symfony/http-client' => 
    array (
      'pretty_version' => '5.x-dev',
      'version' => '5.9999999.9999999.9999999-dev',
      'aliases' => 
      array (
        0 => '9999999-dev',
      ),
      'reference' => 'a408c485b61874e77b678ffb5c2d0fc30377c6eb',
    ),
    'symfony/http-client-contracts' => 
    array (
      'pretty_version' => 'dev-main',
      'version' => 'dev-main',
      'aliases' => 
      array (
        0 => '2.4.x-dev',
      ),
      'reference' => '7e82f6084d7cae521a75ef2cb5c9457bbda785f4',
    ),
    'symfony/http-client-implementation' => 
    array (
      'provided' => 
      array (
        0 => '2.4',
      ),
    ),
    'symfony/polyfill-ctype' => 
    array (
      'pretty_version' => 'dev-main',
      'version' => 'dev-main',
      'aliases' => 
      array (
        0 => '1.23.x-dev',
      ),
      'reference' => '46cd95797e9df938fdd2b03693b5fca5e64b01ce',
    ),
    'symfony/polyfill-intl-grapheme' => 
    array (
      'pretty_version' => 'dev-main',
      'version' => 'dev-main',
      'aliases' => 
      array (
        0 => '1.23.x-dev',
      ),
      'reference' => '053f7184175d5417c933817341c5cc0053ddacd5',
    ),
    'symfony/polyfill-intl-normalizer' => 
    array (
      'pretty_version' => 'dev-main',
      'version' => 'dev-main',
      'aliases' => 
      array (
        0 => '1.23.x-dev',
      ),
      'reference' => '8590a5f561694770bdcd3f9b5c69dde6945028e8',
    ),
    'symfony/polyfill-mbstring' => 
    array (
      'pretty_version' => 'dev-main',
      'version' => 'dev-main',
      'aliases' => 
      array (
        0 => '1.23.x-dev',
      ),
      'reference' => '298b87cbbe99cb2c9f88fb1d1de78833b64b483e',
    ),
    'symfony/polyfill-php73' => 
    array (
      'pretty_version' => 'dev-main',
      'version' => 'dev-main',
      'aliases' => 
      array (
        0 => '1.23.x-dev',
      ),
      'reference' => 'fba8933c384d6476ab14fb7b8526e5287ca7e010',
    ),
    'symfony/polyfill-php80' => 
    array (
      'pretty_version' => 'dev-main',
      'version' => 'dev-main',
      'aliases' => 
      array (
        0 => '1.23.x-dev',
      ),
      'reference' => 'eca0bf41ed421bed1b57c4958bab16aa86b757d0',
    ),
    'symfony/property-access' => 
    array (
      'pretty_version' => '5.x-dev',
      'version' => '5.9999999.9999999.9999999-dev',
      'aliases' => 
      array (
        0 => '9999999-dev',
      ),
      'reference' => '3a9a100a1fefd454bb0fe38de524023e8390da13',
    ),
    'symfony/property-info' => 
    array (
      'pretty_version' => '5.x-dev',
      'version' => '5.9999999.9999999.9999999-dev',
      'aliases' => 
      array (
        0 => '9999999-dev',
      ),
      'reference' => '3349bf2b7702d240e0b2b4eb0086270be1909f49',
    ),
    'symfony/serializer' => 
    array (
      'pretty_version' => '5.x-dev',
      'version' => '5.9999999.9999999.9999999-dev',
      'aliases' => 
      array (
        0 => '9999999-dev',
      ),
      'reference' => 'fc663b9e90c50cfbc0df70d8439a6335ea4c7742',
    ),
    'symfony/service-contracts' => 
    array (
      'pretty_version' => 'dev-main',
      'version' => 'dev-main',
      'aliases' => 
      array (
        0 => '2.4.x-dev',
      ),
      'reference' => 'f040a30e04b57fbcc9c6cbcf4dbaa96bd318b9bb',
    ),
    'symfony/stopwatch' => 
    array (
      'pretty_version' => '5.x-dev',
      'version' => '5.9999999.9999999.9999999-dev',
      'aliases' => 
      array (
        0 => '9999999-dev',
      ),
      'reference' => 'd99310c33e833def36419c284f60e8027d359678',
    ),
    'symfony/string' => 
    array (
      'pretty_version' => '5.x-dev',
      'version' => '5.9999999.9999999.9999999-dev',
      'aliases' => 
      array (
        0 => '9999999-dev',
      ),
      'reference' => '01454c66c88a6bb4449dcdeb913e463e075f331b',
    ),
    'symfony/translation-contracts' => 
    array (
      'pretty_version' => 'dev-main',
      'version' => 'dev-main',
      'aliases' => 
      array (
        0 => '2.4.x-dev',
      ),
      'reference' => '95c812666f3e91db75385749fe219c5e494c7f95',
    ),
    'symfony/validator' => 
    array (
      'pretty_version' => '5.x-dev',
      'version' => '5.9999999.9999999.9999999-dev',
      'aliases' => 
      array (
        0 => '9999999-dev',
      ),
      'reference' => 'd78fe90b5b9ae0f7a0ddae17d438b95dce57c6ae',
    ),
    'symfony/var-dumper' => 
    array (
      'pretty_version' => '5.x-dev',
      'version' => '5.9999999.9999999.9999999-dev',
      'aliases' => 
      array (
        0 => '9999999-dev',
      ),
      'reference' => 'ff77601582f1ffe003ca43f3860e096d0937a96a',
    ),
    'theseer/tokenizer' => 
    array (
      'pretty_version' => '1.2.0',
      'version' => '1.2.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '75a63c33a8577608444246075ea0af0d052e452a',
    ),
    'vimeo/psalm' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '4.x-dev',
      ),
      'reference' => 'ecd5e3b7ae7196aaea10befd02a8d18bbf631533',
    ),
    'webmozart/assert' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '1.10.x-dev',
      ),
      'reference' => 'f07851c5b43e4cb502c24068620e9af6cbdd953f',
    ),
    'webmozart/path-util' => 
    array (
      'pretty_version' => '2.3.0',
      'version' => '2.3.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd939f7edc24c9a1bb9c0dee5cb05d8e859490725',
    ),
  ),
);
private static $canGetVendors;
private static $installedByVendor = array();







public static function getInstalledPackages()
{
$packages = array();
foreach (self::getInstalled() as $installed) {
$packages[] = array_keys($installed['versions']);
}


if (1 === \count($packages)) {
return $packages[0];
}

return array_keys(array_flip(\call_user_func_array('array_merge', $packages)));
}









public static function isInstalled($packageName)
{
foreach (self::getInstalled() as $installed) {
if (isset($installed['versions'][$packageName])) {
return true;
}
}

return false;
}














public static function satisfies(VersionParser $parser, $packageName, $constraint)
{
$constraint = $parser->parseConstraints($constraint);
$provided = $parser->parseConstraints(self::getVersionRanges($packageName));

return $provided->matches($constraint);
}










public static function getVersionRanges($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

$ranges = array();
if (isset($installed['versions'][$packageName]['pretty_version'])) {
$ranges[] = $installed['versions'][$packageName]['pretty_version'];
}
if (array_key_exists('aliases', $installed['versions'][$packageName])) {
$ranges = array_merge($ranges, $installed['versions'][$packageName]['aliases']);
}
if (array_key_exists('replaced', $installed['versions'][$packageName])) {
$ranges = array_merge($ranges, $installed['versions'][$packageName]['replaced']);
}
if (array_key_exists('provided', $installed['versions'][$packageName])) {
$ranges = array_merge($ranges, $installed['versions'][$packageName]['provided']);
}

return implode(' || ', $ranges);
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getVersion($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

if (!isset($installed['versions'][$packageName]['version'])) {
return null;
}

return $installed['versions'][$packageName]['version'];
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getPrettyVersion($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

if (!isset($installed['versions'][$packageName]['pretty_version'])) {
return null;
}

return $installed['versions'][$packageName]['pretty_version'];
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getReference($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

if (!isset($installed['versions'][$packageName]['reference'])) {
return null;
}

return $installed['versions'][$packageName]['reference'];
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getRootPackage()
{
$installed = self::getInstalled();

return $installed[0]['root'];
}







public static function getRawData()
{
return self::$installed;
}



















public static function reload($data)
{
self::$installed = $data;
self::$installedByVendor = array();
}




private static function getInstalled()
{
if (null === self::$canGetVendors) {
self::$canGetVendors = method_exists('Composer\Autoload\ClassLoader', 'getRegisteredLoaders');
}

$installed = array();

if (self::$canGetVendors) {

foreach (ClassLoader::getRegisteredLoaders() as $vendorDir => $loader) {
if (isset(self::$installedByVendor[$vendorDir])) {
$installed[] = self::$installedByVendor[$vendorDir];
} elseif (is_file($vendorDir.'/composer/installed.php')) {
$installed[] = self::$installedByVendor[$vendorDir] = require $vendorDir.'/composer/installed.php';
}
}
}

$installed[] = self::$installed;

return $installed;
}
}
