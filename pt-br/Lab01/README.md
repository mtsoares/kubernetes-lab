# Lab 01

Neste lab, instalaremos um cluster de Kubernetes simples de um único nó, usando a distribuição K3s. Para mais detalhes sobre K3s, consulte a documentação oficial: https://k3s.io/ (isso não é obrigatório para este lab).

Você vai precisar das seguintes informações do seu instrutor ou da sua instrutora:
* Um IP e um FQDN para a instância do lab.
* Uma chave privada SSH que permita a conexão SSH na instância do lab.
* Um nome de usuário para acessar a instância. (Este manual pressupõe que o nome de usuário será “ubuntu”).
* Um nome de servidor de banco de dados e uma senha de admin. 

Este manual usa “nano” para editar arquivos, mas você pode usar qualquer editor de texto de sua preferência.

Siga as instruções a seguir para a instalação e configuração.

## Preparação

Antes da instalação, você vai precisar de uma configuração específica (não padrão) para evitar problemas com certificados em labs futuros.

Edite o seguinte arquivo (como root): 

```bash
sudo mkdir -p /etc/rancher/k3s/
sudo nano /etc/rancher/k3s/config.yaml 
```

Adicione as informações a seguir, substituindo \<FQDN\> pelo FQDN da VM desse lab, fornecido pelo instrutor ou instrutora:

```yaml
tls-san:
  - "<FQDN>"
```

Salve o arquivo (CTRL+O) e saia (CTRL+X).

Clone o repositório do lab no diretório home “ubuntu”:

```bash
cd ~
git clone https://github.com/mtsoares/kubernetes-lab.git
```

## Instalação

No seu cliente SSH conectado à instância de Linux desse lab, execute o seguinte comando para instalar o K3s e todas as ferramentas kube* para administrá-lo (kubeadm, kubectl):

```bash
curl -sfL https://get.k3s.io | INSTALL_K3S_VERSION=v1.27.9+k3s1 sh -
```

*NOTA:* A versão está especificada para que todos os Helm charts que vamos instalamos funcionem corretamente. Normalmente, não é necessário especificar uma versão do k3s para que a mais recente seja usada.

*AVISO:* O comando acima não deve ser executado em produção. Antes de executá-lo em ambientes de produção, leia primeiro a documentação.

Quando a instalação estiver concluída, o arquivo de configuração para acessar o cluster K3s está na pasta /etc na VM de Linux. Os comandos a seguir criarão uma pasta .kube no diretório do usuário atual. Copie o arquivo config e defina as permissões apropriadas.

```bash
mkdir ~/.kube
sudo cp /etc/rancher/k3s/k3s.yaml ~/.kube/config
sudo chown ubuntu ~/.kube/config
```

Agora, você deverá definir a variável de ambiente correta, para que o kubectl saiba qual arquivo config usar:

```bash
export KUBECONFIG=~/.kube/config
```

Teste para ver se é possível verificar as informações do cluster:

```bash
kubectl get nodes
```

O comando a seguir manterá a variável de ambiente KUBECONFIG. Isso significa que, na próxima vez que o usuário “ubuntu” fizer login, o kubectl ainda estará funcionando:

```bash
echo -e "KUBECONFIG=/home/ubuntu/.kube/config" | sudo tee -a /etc/environment
```

Execute cada um dos comandos a seguir e analise os resultados:

```bash
kubectl cluster-info
kubectl get nodes -o wide
kubectl get namespaces
kubectl get services --all-namespaces
kubectl get pods -o wide --all-namespaces
```

Usando um arquivo txt no seu próprio computador, responda às seguintes perguntas (se não tiver certeza sobre alguma delas, pergunte ao seu instrutor ou à sua instrutora):

* Quantos namespaces existem no cluster?
* Qual namespace tem mais pods?
* O cluster está usando traefik ou nginx como Ingress Controller?
* Quantos nós existem no cluster?
* Quais são os nomes dos nós? 

## Desafio

Usando as informações reunidas até agora, tente configurar seu computador pessoal para acessar o cluster que você acabou de instalar usando o kubectl. Se precisar de ajuda, peça ao seu instrutor ou à sua instrutora. No link a seguir tem algumas referências que podem te ajudar:

* https://kubernetes.io/docs/tasks/tools/
