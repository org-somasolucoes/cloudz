# CloudZ

## Bem-vindo ao CloudZ
"CloudZ" é uma biblioteca (library) desenvolvida para aqueles que precisam interagir com vários serviços em nuvem de maneira genérica e fácil. Com esta biblioteca, é possível enviar arquivos para diferentes serviços em nuvem usando uma única estrutura, tornando o processo mais simples e intuitivo.

## Estratégias do CloudZ
- FTP
- SFTP
- AWS S3

## Instruções de instalação
~~~clone
Para instalar o CloudZ, basta clonar o repositório e inserir a pasta no seu projeto. Embora essa forma de instalação seja provisória, ela permite que você comece a usar a biblioteca imediatamente.
```
git clone <https://github.com/org-somasolucoes/cloudz.git>
```
~~~

>No futuro, o CloudZ será disponibilizado em gerenciadores de pacotes, o que tornará a instalação ainda mais fácil e conveniente.

## Inicialização
Para utilizar o CloudZ, você precisará instanciar duas classes: CloudService e CloudServiceFacade. A primeira classe requer três parâmetros, enquanto a segunda requer apenas um parâmetro.

~~~cloudservice
1. Código do serviço em nuvem que você deseja usar;
2. Tipo de serviço em nuvem que você está conectando;
3. Código de referência da conta de serviço em nuvem que você deseja acessar.
```
new CloudService($cloudServiceCode, $typeAccount, $accountCode);
```
~~~

~~~cloudservicefacade
1. Instancia da classe CloudService.
```
$cloudService = new CloudService($cloudServiceCode, $typeAccount, $accountCode);
new CloudServiceFacade($cloudService);
```
~~~

Ao fornecer esses parâmetros, você poderá iniciar o uso da biblioteca e interagir com diferentes serviços em nuvem usando uma única estrutura.

## Configuração
A classe CloudServiceFacade permite algumas configurações opcionais:

~~~descrição
- canEncryptName: Permite que o nome do arquivo seja criptografado durante o envio;
- canDeleteAfterUpload: Permite que o arquivo salvo localmente seja excluído após o upload;
- path: Permite criar ou acessar um diretório específico na nuvem.
~~~

~~~comandos
$cloudServiceFacade->settings->add('canEncryptName', false);
$cloudServiceFacade->settings->add('canDeleteAfterUpload', false);
$cloudServiceFacade->settings->add('path', 'c:\example');
~~~

>Se você deseja manter a organização na nuvem seguindo um padrão, a biblioteca CloudZ fornece uma "utility" de diretório. Essa utilidade oferece um padrão focado em soluções de projeto, que pode ser utilizado passando como parâmetro três strings: a raiz, o nome da solução e o nome do módulo.
>Para integrações de projetos externos, a "utility" de diretório necessita apenas de duas strings como parâmetro: a raiz e o nome da integração. 
>Essa "utility" pode ser acessada de forma estática, facilitando a sua utilização no seu projeto.
```
CloudServicePathUtility::mountSolutionPath('root', 'solutionName', 'module');
CloudServicePathUtility::mountIntegrationPath('root', 'integrationName');
```
>Com essa funcionalidade, você pode manter uma estrutura organizada em diferentes serviços em nuvem, tornando mais fácil a localização dos seus arquivos e projetos.

Ao utilizar essas configurações, você pode personalizar ainda mais a interação com os serviços em nuvem e adequá-las às suas necessidades específicas.

## Preparação do arquivo
Antes de realizar operações como Upload ou Delete, é necessário preparar o arquivo a ser enviado. Para isso, é preciso instanciar a classe CloudServiceFile, passando como parâmetro o caminho local do arquivo desejado.

```
new CloudServiceFile($filePath);
```

## Upload
Para realizar a ação de upload, basta utilizar o método "upload" da instância da Facade, passando como parâmetro o arquivo preparado previamente pela classe CloudServiceFile.

```
$cloudService = new CloudService($cloudServiceCode, $typeAccount, $accountCode);
$cloudServiceFacade = new CloudServiceFacade($cloudService);
$file = new CloudServiceFile($filePath);

$cloudServiceFacade->upload($file);
```

>Ao utilizar o método upload, a biblioteca retorna um objeto de resposta que contém informações relevantes para o usuário. Em caso de sucesso, o código 200 é retornado junto com uma URL que permite acessar o arquivo enviado. Em caso de erro, o código 400 é retornado junto com uma mensagem que explica o motivo do erro ocorrido.

## Delete
Para realizar a ação de delete, basta utilizar o método "delete" da instancia da Facade, passando como parâmetro o arquivo preparado previamente pela classe CloudServiceFile.

```
$cloudService = new CloudService($cloudServiceCode, $typeAccount, $accountCode);
$cloudServiceFacade = new CloudServiceFacade($cloudService);
$file = new CloudServiceFile($filePath);

$cloudServiceFacade->delete($file);
```

>Ao utilizar o método delete, a biblioteca retorna um objeto de resposta que contém informações relevantes para o usuário. Em caso de sucesso, o código 200 é retornado junto com uma mensagem de sucesso. Em caso de erro, o código 400 é retornado junto com uma mensagem que explica o motivo do erro ocorrido.