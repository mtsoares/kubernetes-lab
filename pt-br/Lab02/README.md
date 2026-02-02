# Lab 02

Neste lab, vamos criar dois pods: um pod de exemplo e um pod de WordPress com acesso a um banco de dados MySQL, permitindo que o WordPress seja executado com sucesso.

## Criando um pod simples

Usando um editor de texto, crie um arquivo chamado nginx-pod.yml:

```bash
nano nginx-pod.yml
```

Adicione o seguinte conteúdo ao arquivo, usando a versão mais recente do pod nginx official no Docker Hub:

```yaml
apiVersion: v1
kind: Pod
metadata:
  name: ## Replace here by a suitable pod name ##
spec:
  containers:
  - name: ## Replace here by a suitable container name ##
    image: ## Replace here by a suitable image for nginx ##
```

Aplique o arquivo:

```bash
kubectl apply -f nginx-pod.yml
```

Verifique se o pod com o nome fornecido iniciou e veja os seus detalhes:

```bash
kubectl get pods
kubectl get pods -o wide
```

Verifique os logs do pod:

```bash
kubectl logs PODNAME
```

Verifique as informações do pod:

```bash
kubectl describe pod PODNAME
```

Adicione o seguinte conteúdo à seção “metadata” do Pod. Em seguida, aplique o arquivo usando o mesmo comando que você usou antes:

```yaml
  labels:
    app: nginx-pod
```

Verifique os logs para ver se há algum pod com essa etiqueta:

```bash
kubectl logs --selector app=nginx-pod
```

Deixe o pod em execução para um lab posterior.

## Preparação para a implementação do Wordpress

Usando a documentação oficial do container de Wordpress da Docker em https://hub.docker.com/_/wordpress, encontre e anote as variáveis de ambiente necessárias para uma implementação correta do Wordpress:

* Variável para o hostname/IP do servidor de banco de dados.
* Variável para o nome de usuário do banco de dados.
* Variável para a senha do usuário do banco de dados.
* Variável para o nome do banco de dados no servidor.

Peça as seguintes informações ao seu instrutor ou à sua instrutora:

* Um IP/FQDN para o servidor de banco de dados.
* O nome do banco de dados que você deve usar.
* Um nome de usuário e uma senha para acessar o banco de dados.

Essas informações serão utilizadas em labs futuros.

Crie um namespace para a implementação do WordPress e defina-o como o namespace atual:

```bash
kubectl create namespace wordpress-apps
kubectl config set-context --current --namespace=wordpress-apps
```

## Criando um pod WordPress

Acesse a pasta do Lab02, que contém alguns arquivos de suporte:

```bash
cd ~/kubernetes-lab/Lab02
```

Edite o arquivo wordpress-pod.yml adicionando as informações necessárias. Quando terminar, aplique o arquivo criado e verifique se o pod iniciou de forma correta:

* Obtenha todos os pods no namespace wordpress-apps atual e verifique seus status.
* Verificando os registros do pod que você criou.
(Use os mesmos comandos que você já usou nos labs anteriores.)

Se o pod não estiver iniciando ou tiver um erro, verifique a configuração. Se o problema persistir, peça ajuda ao seu instrutor ou à sua instrutora.
