# Lab 06

Neste lab, vamos instalar no cluster uma solução de monitoramento baseada em Prometheus e Grafana e, em seguida, acessá-las externamente.

## Preparação

Crie um namespace para a infraestrutura de monitoramento:

```bash
kubectl create namespace monitoring
kubectl config set-context --current --namespace=monitoring
```

Acesse a pasta do Lab06, que contém os arquivos de configuração para as ferramentas de monitoramento. Não é preciso alterar nenhum arquivo de configuração:

```bash
cd ~/kubernetes-lab/Lab06/
```

Instale a ferramenta helm na VM de Linux:

```bash
sudo snap install helm --classic
```

Adicione o repositório padrão do Prometheus ao helm local:

```bash
helm repo add prometheus-community https://prometheus-community.github.io/helm-charts
```

Instale o stack de monitoramento:

```bash
helm upgrade --install prometheus prometheus-community/kube-prometheus-stack --version 39.13.3 --values kube-prometheus-stack-values.yaml
```

Verifique se todos os pods para o monitoramento estão ativos:

```bash
kubectl get pods
```

Verifique o service de NodePort criado para o Grafana:

```bash
kubectl get svc
```

Procure um serviço chamado “prometheus-grafana” e verifique qual é o NodePort exposto para ele (deve ser 3XXXX). Abra um navegador e acesse http://\<VM_FQDN_OR_IP\>:\<NODEPORT\>. As credenciais são admin/prom-operator.

Acesse o painel chamado “Kubernetes / Compute Resources / Cluster” e observe os dados exibidos. Como os painéis não estão configurados de forma correta, é possível que alguns dados não estejam sendo exibidos. Se você tiver conhecimento do Grafana, tente corrigir alguns dos widgets.

Depois do teste, exlua o stack de monitoramento para economizar recursos do lab:

```bash
helm uninstall prometheus
```

## Instalando o Rancher Manager

A comunidade Rancher Manager é uma ferramenta opensource que oferece uma UI gráfica para administrar vários clusters, incluindo aquele no qual o Manager for instalado.

Instale o cert-manager para que o Rancher possa gerar certificados:

```bash
kubectl apply -f https://github.com/cert-manager/cert-manager/releases/download/v1.14.2/cert-manager.crds.yaml
helm repo add jetstack https://charts.jetstack.io
helm repo update
helm install cert-manager jetstack/cert-manager --namespace cert-manager --create-namespace
```

Crie um namespace para o Rancher Manager:

```bash
kubectl create namespace cattle-system
kubectl config set-context --current --namespace=cattle-system
```

Adicione o repositório Helm para o Rancher:

```bash
helm repo add rancher-latest https://releases.rancher.com/server-charts/latest
helm repo update
```

Execute o seguinte comando para realizar a instalação:

```bash
helm install rancher rancher-latest/rancher --namespace cattle-system --set hostname=<LAB_VM_FQDN> --set bootstrapPassword=admin123
```

Aguarde até que todos os pods do Rancher estejam ativos, verificando com “kubectl get pods”. Isso pode demorar uns 20 minutos. Todos os pods devem estar no status “Successful” ou “Running”.

Em um navegador web, acesse https://\<LAB_VM_FQDN\> e forneça a seguinte senha de bootstrap: admin123 (fornecida no comando de instalação). Crie um nome de usuário e uma senha, faça login e verifique o cluster “local”:

* Verifique se todos os namespaces estão disponíveis para a navegação.
* Verifique se todos os Pods e Implementações criados estão visíveis.
* Verifique se todos os services e ingresses estão disponíveis.
* Tente administrar o cluster “local” usando o arquivo kubeconfig oferecido pelo Rancher Manager.
