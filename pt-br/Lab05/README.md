# Lab 05

Este lab vai mostrar como administrar uma implementação de Kubernetes, criando e escalando um app chamado "demoapp".

## Preparação

Peça as seguintes informações ao seu instrutor / instrutora:

* Um FQDN para o demoapp, para a configuração do Ingress.
* URL de imagem para o demoapp.
* Credenciais e servidor para o registro do demoapp.

O demoapp usa as seguintes variáveis de ambiente para a configuração:

* DATABASE_SERVER: Nome do servidor de banco de dados MySQL (já fornecido pelo instrutor no Lab 02).
* DATABASE_USERNAME: demoappuser
* DATABASE_PASSWORD: securepassword
* DATABASE_NAME: demoappdb

Todas as quatro variáveis de ambiente devem ser configuradas na implementação neste lab.

## Criando um namespace 

Crie um namespace para o novo app e mude para ele:

```bash
kubectl create namespace demoapp
kubectl config set-context --current --namespace=demoapp
```

Como a imagem do demoapp está hospedada em um registro de container que não é Docker Hub, e que ela está protegida por autenticação via senha, você precisa informar explicitamente a qualquer pod as credenciais para fazer login. Para isso, use o seguinte comando para criar um segredo de registro do Docker:

```bash
kubectl create secret docker-registry \<PROVIDE A NAME FOR THE SECRET\> --docker-server=\<DOCKER_REGISTRY_SERVER\> --docker-username=\<DOCKER_REGISTRY_USERNAME\> --docker-password=\<DOCKER_REGISTRY_PASSWORD\>
```

Verifique se o segredo foi criado com "kubectl get secrets" e anote seu nome.

Mude para a pasta do Lab05 e edite o arquivo demoapp-deployment.yml:

```bash
cd ~/kubernetes-lab/Lab05
nano demoapp-depoyment.yml
```

Configure ele adicionando as informações necessárias no arquivo. Em seguida, aplique o arquivo e verifique se a implementação e os pods iniciaram de forma correta:

```bash
kubectl get deployments
kubectl get pods
```

## Habilitando o acesso externo ao demoapp

Crie um service de ClusterIP para o demoapp editando o arquivo demoapp-service.yml e, em seguida, aplique o arquivo do service.

Crie um Ingress para o demoapp editando o arquivo demoapp-ingress.yml e, em seguida, aplique o arquivo do ingress.

Teste o aplicativo abrindo um navegador e acessando http://\<DEMOAPP_FQDN\>/demoapp.php. Este aplicativo mostra algumas informações que vão ser úteis para o diagnóstico de problemas e para a visibilidade:

* As variáveis de ambiente fornecidas.
* O nome do pod que executa o container.
* Uma lista de alunos adicionada ao banco de dados MySQL estando conectado.

## Escalando a implementação

Edite o arquivo demoapp-deployment.yml e altere a quantidade de réplicas de 1 a 3. Aplique o arquivo e:

* Verifique se o número de réplicas mudou, usando "kubectl get deployments".
* Atualize o navegador e verifique o campo do Pod. Ele deve mudar para refletir de qual pod o aplicativo está sendo executado.
* Verifique os nomes dos pods executando “kubectl get pods” e verifique se os nomes dos pods correspondem aos que você vê no aplicativo.

## Criando um configMap para configurações comuns

Os ConfigMaps são úteis para reutilizar a mesma configuração em vários pods e implementações. No demoapp, se vários aplicativos estiverem sendo executados no mesmo cluster e se o servidor de banco de dados for sempre o mesmo, será interessante centralizar a configuração.

Edite o arquivo demoapp-configmap.yml e adicione as informações solicitadas sobre o servidor de banco de dados no arquivo. Aplique o arquivo e verifique se o configmap foi criado:

```bash
kubectl get configmaps
```

Edite o arquivo demoapp-deployment.yml e remova a variável de ambiente DATABASE_SERVER e o seu valor. Adicione o seguinte conteúdo abaixo da configuração do container (logo abaixo da configuração da imagem):

```yaml
        envFrom:
        - configMapRef:
            name: <NAME OF THE CREATED SCONFIGMAP>
```

Aplique a configuração de novo e verifique se o demoapp ainda funciona, exibindo as informações do banco de dados MySQL. Se não, verifique de novo a implementação ou peça ajuda ao seu instrutor / instrutora.

## Criando um segredo para armazenar senhas

Os segredos são ferramentas úteis para ocultar informações confidenciais no Kubernetes. No demoapp, a senha do banco de dados é armazenada como uma variável de ambiente e pode ser movida para um segredo.

Para armazenar um segredo, você precisa codificar a senha do banco de dados usando o algoritmo base64. Para fazer isso, use o seguinte comando:

```bash
printf '<PUT HERE THE DATABSAE PASSWORD>' | base64
```

Copie o output como a senha codificada. Agora, edite o arquivo demoapp-secret.yml, adicionando a senha codificada onde for indicado. Aplique o arquivo e, com “kubectl get secrets”, verifique se ele está listado.

Edite o arquivo demoapp-deployment e adicione o seguinte comando logo abaixo do nome do configMapRef que você adicionou antes (dentro do objeto envFrom):

```yaml
        - secretRef:
            name: <NAME OF SECRET CONFIGURED>
```

Deverá ficar assim:

```yaml
        envFrom:
        - configMapRef:
            name: <NAME OF THE CREATED SCONFIGMAP>
        - secretRef:
            name: <NAME OF SECRET CONFIGURED>

```

Salve e aplique o arquivo. Teste se o demoapp ainda está funcionando de forma correta.

## Usando ConfigMaps para montar arquivos dentro do pod

Os Configmaps podem ser usados para armazenar qualquer tipo de informação. Neste exercício, você vai configurar um arquivo html como um configmap injetado nos Pods como um volume.

Crie um arquivo HTML chamado index.html na VM de Linux com a informação que você quiser. Confira o exemplo a seguir:

```html
<!DOCTYPE html>
<html>
<head>
    <title>HTML file on a great training!</title>
</head>
 
<body>
    <h1 style="color:Blue"> 
        Great training and great lab!  
    </h1>
Will definitely recommend.
</body>
</html>
```

Crie um configmap a partir do arquivo criado:

```bash
kubectl create configmap demoapp-newindex-configmap --from-file ./index.html
```

Verifique o conteúdo do configmap no formato YAML:

```bash
kubectl get configmap demoapp-newindex-configmap -o yaml
```

Adicione o seguinte conteúdo no final do arquivo demoapp-deployment.yml:

```yaml
        volumeMounts:
        - mountPath: /var/www/html/index.html
          name: demoapp-index
          subPath: index.html
      volumes:
      - name: demoapp-index
        configMap:
          name: demoapp-newindex-configmap
```

* volumeMounts deverá estar dentro do "container:" object
* volumes deverá estar dentro do spec: (PodSpec).

Aplique o arquivo alterado e verifique se o demoapp está funcionando de forma correta. Em seguida, tente acessar http://\<DEMOAPP_FQDN\>/ (sem nenhum demoapp.php nele) e verifique se o seu arquivo HTML funciona.
