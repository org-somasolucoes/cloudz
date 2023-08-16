# CloudZ

## Bem-vindo ao CloudZ Library
O CloudZ é uma biblioteca desenvolvida para facilitar a interação com serviços em nuvem de maneira genérica e intuitiva.

## Estratégias do CloudZ
- FTP
- SFTP
- AWS S3

## Instruções de instalação
A biblioteca CloudZ está disponível para uso no gerenciado de pacotes Composer, para utilizar a mesma, use o seguinte comando:

```bash
composer require somasolucoes/cloudz
```

## Guia de Inicialização da Biblioteca CloudZ em PHP

### Configuração Inicial
Antes de começar a utilizar a biblioteca CloudZ, é necessário criar arquivos JSON para fornecer informações de configuração para diferentes serviços de nuvem. Para isso, siga as etapas abaixo:
1. Crie uma pasta chamada `.cloudz` na raiz do seu projeto.
2. Dentro da pasta `.cloudz`, crie arquivos JSON separados para cada serviço de nuvem que você pretende utilizar, seguindo o padrão de nomenclatura a seguir:
    - `ftp.json`
    - `aws-s3.json`

#### Configuração para FTP/SFTP

Crie um arquivo `ftp.json` dentro da pasta `.cloudz` com as informações necessárias para fazer login na conta de servço FTP ou SFTP. Utilize o seguinte formato JSON:

```json
{
    "FTPAccount": 
        {
            "code": 1,
            "host": "192.168.0.0",
            "port": 21/22,
            "user": "admin",
            "password": "123",
            "isPassive": "S/N",
            "dirWork": "/example",
            "urlAcess": "http://example",
            "useSSH": "S/N"
        }
}
```
#### Configuração para AWS-S3

Crie um arquivo `aws-s3.json` dentro da pasta `.cloudz` com as informações necessárias para autenticar na conta do Amazon S3. Utilize o seguinte formato JSON:

```json
{
    "AWSS3Account": 
    {
        "code": 1,
        "AWSKey": "B54CAB3F16A6CB378AEE5E1132B225F2",
        "AWSSecretKey": "5918933c23ef6c8ee8e3a96807654b3d",
        "AWSRegion": "us-east-1",
        "AWSType": "S3",
        "bucketName": "imagens/vídeos"
    }
}
```

Para suportar o upload de arquivos para várias contas, você pode criar uma lista de contas no arquivo JSON. Veja os exemplos abaixo:

#### Configuração para FTP/SFTP com Múltiplas Contas

Crie um arquivo `ftp.json` na pasta `.cloudz` e configure mútiplas contas usando uma matriz JSON:

```json
{
    "FTPAccount": [
        {
            "code": 1,
            "host": "192.168.0.0",
            ...
        },
        {
            "code": 2,
            "host": "192.168.0.1",
            ...
        }
    ]
}
```

#### Configuração para AWS-S3 com Múltiplas Contas

Crie um arquivo `aws-s3.json` na pasta `.cloudz` e configure mútiplas contas usando uma matriz JSON:

```json
{
    "AWSS3Account": [
    {
        "code": 1,
        "AWSKey": "B54CAB3F16A6CB378AEE5E1132B225F2",
        ...
    },{
        "code": 2,
        "AWSKey": "B54CAB3F16A6CB378AEE5E1132B225F2",
        ...
    }]
}
```

### Iniciando utilização
Após as configurações prévias, para iniciar a utilização da CloudZ, você precisará instanciar a classes: CloudService. Ela requer dois parâmetros.

1. Tipo de serviço em nuvem que você está conectando;
2. Código de referência da conta de serviço em nuvem que você deseja acessar (Esse código irá identificar uma conta configurada em um arquivo JSON dentro do diretório `.cloudz`).
```php
$cloudServiceType = 'tipo_servico';
$cloudServiceAccountCode = 1;
$cloudService = new CloudService($cloudServiceType, $cloudServiceAccountCode);
```
Segue lista dos tipos de serviços disponíveis:
- 'FTP'
- 'AWS-S3'

Exemplo:
```php
$cloudServiceType = 'FTP';
$cloudServiceAccountCode = 1;
$cloudService = new CloudService($cloudServiceType, $cloudServiceAccountCode);
```

Mas pode ser usado também, constantes dos tipos de serviços disponibilizado pela biblioteca.
Segue lista de constantes dos tipos de serviços:
- FTP_ACCOUNT
- AWS_S3_ACCOUNT

Exemplo:
```php
$cloudServiceType = CloudServiceTypes::FTP_ACCOUNT;
$cloudServiceAccountCode = 1;
$cloudService = new CloudService($cloudServiceType, $cloudServiceAccountCode);
```

Ao fornecer esses parâmetros, você poderá iniciar o uso da biblioteca e interagir com diferentes serviços em nuvem usando uma única estrutura.

## Configuração
Para adicionar uma configuração, basta usar o seguinte comando:

```php
$cloudServiceType = 'tipo_servico';
$cloudServiceAccountCode = 1;
$cloudService = new CloudService($cloudServiceType, $cloudServiceAccountCode);
$cloudService->settings->add('key example', value example);
```

A classe CloudService permite algumas configurações opcionais:

- canEncryptName: Permite que o nome do arquivo seja criptografado durante o envio (valor booleano true/false);
- canDeleteAfterUpload: Permite que o arquivo salvo localmente seja excluído após o upload (valor booleano true/false);
- path: Permite criar ou acessar um diretório específico na nuvem (valor string).

>Se você deseja manter a organização na nuvem seguindo um padrão, a biblioteca CloudZ fornece "utilitys" de diretório. Essa utilidade oferece um padrão focado em soluções de projeto, que pode ser utilizado passando como parâmetro três strings: a raiz, o nome da solução e o nome do módulo.
Para integrações de projetos externos, a "utility" de diretório necessita apenas de duas strings como parâmetro: a raiz e o nome da integração. 
Essa "utility" pode ser acessada de forma estática, facilitando a sua utilização no seu projeto.

Exemplo:
```php
$mountSolutionPath = CloudServicePathUtility::mountSolutionPath('root', 'solutionName', 'module');
//Resultado de CloudServicePathUtility::mountSolutionPath: 'root/solucoes/solutionName/module'
$mountIntegrationPath = CloudServicePathUtility::mountIntegrationPath('root', 'integrationName');
//Resultado de CloudServicePathUtility::mountIntegrationPath: 'root/integracoes/integrationName'

$cloudServiceType = 'tipo_servico';
$cloudServiceAccountCode = 1;
$cloudService = new CloudService($cloudServiceType, $cloudServiceAccountCode);
$cloudService->settings->add('path', $mountSolutionPath);
```
>Com essa funcionalidade, você pode manter uma estrutura organizada em diferentes serviços em nuvem, tornando mais fácil a localização dos seus arquivos e projetos.

## Preparação do arquivo
Antes de realizar operações como Upload ou Delete, é necessário preparar o arquivo a ser enviado. Para isso, é preciso instanciar a classe CloudServiceFile, passando como parâmetro o caminho local do arquivo desejado.
```php
$filePath = 'C:\Documents\example.txt'
$file = new CloudServiceFile($filePath);
```

## Upload
Para realizar a ação de upload, basta utilizar o método "upload" da instância da CloudService, passando como parâmetro o arquivo preparado previamente pela classe CloudServiceFile.
```php
$cloudServiceType = 'tipo_servico';
$cloudServiceAccountCode = 1;
$cloudService = new CloudService($cloudServiceType, $cloudServiceAccountCode);

$filePath = 'C:\Documents\example.txt'
$file = new CloudServiceFile($filePath);

$cloudService->upload($file);
```

>Ao utilizar o método upload, a biblioteca retorna um objeto de resposta que contém informações relevantes para o usuário. Em caso de sucesso, o código 200 é retornado junto com uma URL que permite acessar o arquivo enviado. Em caso de erro, o código 400 é retornado junto com uma mensagem que explica o motivo do erro ocorrido.

## Delete
Para realizar a ação de delete, basta utilizar o método "delete" da instancia da CloudService, passando como parâmetro o arquivo preparado previamente pela classe CloudServiceFile.
```php
$cloudServiceType = 'tipo_servico';
$cloudServiceAccountCode = 1;
$cloudService = new CloudService($cloudServiceType, $cloudServiceAccountCode);

$filePath = 'C:\Documents\example.txt'
$file = new CloudServiceFile($filePath);

$cloudService->delete($file);
```

>Ao utilizar o método delete, a biblioteca retorna um objeto de resposta que contém informações relevantes para o usuário. Em caso de sucesso, o código 200 é retornado junto com uma mensagem de sucesso. Em caso de erro, o código 400 é retornado junto com uma mensagem que explica o motivo do erro ocorrido.