# Lab 03

Neste lab, vamos fornecer acesso externo tanto para o app de exemplo quanto para o app de WordPress por meio de services e ingresses.

## Preparação

Para este lab, peça ao seu ou à sua instrutora o FQDN do Wordpress que você deve usar.

## Criando um service de NodePort para o pod nginx de exemplo

O pod Nginx de exemplo que criamos no lab anterior ainda está inacessível de qualquer lugar. Vamos criar um service de NodePort para expô-lo em um port TCP na faixa de 30000-32000 nos nós do Kubernetes.

Modifique o namespace de volta para o "default" e verifique que o pod nginx que você criou no Lab 02 está lá e funcionando de forma correta:

```bash
kubectl config set-context --current --namespace=default
kubectl get pods
```

Acesse a pasta do Lab03 e edite o arquivo nginx-service.yml:

```bash
cd ~/kubernetes-lab/Lab03/
nano nginx-service.yml
```

Altere os valores pedidos no arquivo para que sejam os mesmos do pod nginx. Em seguida, aplique o arquivo. Se precisar de mais informações, consulte com o seu instrutor / instrutora.

Verifique se o service iniciou e se ele aparece listado com o nome fornecido:

```bash
kubectl get services
```

Na coluna PORT do service que você acabou de criar, você vai notar que um port de nó foi selecionado de forma aleatória. (ele vai aparecer como 80:3XXXX/TCP, onde 3XXXX é o Port TCP exposto no nó Kubernetes). Anote o Port TCP configurado no seu Notepad.

Agora, num navegador web, tente acessar o pod nginx usando http://\<YOUR VM FQDN OR IP\>:3XXXX (substitua 3XXXX pelo Port listado no comando anterior). Uma página de “Welcome to NGINX” deverá aparecer.

*DESAFIO*: usando a documentação em: https://kubernetes.io/docs/reference/kubernetes-api/service-resources/service-v1/, tente deixar o nodePort FIXO e não ALEATÓRIO.

Nenhuma outra ação será realizada com o pod nginx, mas se você quiser experimentar outras configurações adicionais, peça exemplos ao seu instrutor / instrutora ou experimente por conta própria.

## Expondo o pod do WordPress para poder accesá-lo pela Internet usando o port HTTP padrão e um Ingress

Mude para o namespace wordpress-apps usando:

```bash
kubectl config set-context --current --namespace=wordpress-apps
```

Na pasta do Lab03, edite o arquivo wordpress-service.yml e altere as informações apropriadas:
* O tipo de service deverá ser ClusterIP. Isso vai permitir que o service possa ser usado pelo Ingress que você vai criar a seguir.
* Verifique que o Pod Selector esteja configurado com o mesmo label que o pod de WordPress está usando.
* Você pode verificar o Pod Selector usando o arquivo yml aplicado para o pod ou com: kubectl describe pod \<PODNAME\>.

Aplique o arquivo e verifique se o serviço está funcionando de forma correta:

```bash
kubectl get services
```

Se precisar de ajuda, peça ao seu instrutor ou à sua instrutora.

No diretório do Lab03, edite o arquivo wordpress-ingress.yml e altere as informações apropriadas:
* O FQDN do Wordpress fornecido para este estudante (cada estudante tem um FQDN único).
* O nome do service que você criou antes.
* O Port do service que você criou antes.

Aplique o arquivo wordpress-ingress.yml e verifique se o Ingress foi configurado de forma correta:

```bash
kubectl get ingress
```

Abra uma aba no navegador e tente acessar http://\<WORDPRESS_FQDN\>. Conclua a configuração do Wordpress (anotando no seu Notepad o nome de usuário e a senha configurados) e teste o fazer login na URL http://\<WORDPRESS_FQDN\>/wp-admin.
