# Lab 04

Neste lab, vamos trabalhar com persistência de dados no Pod de Wordpress que criamos no Lab 02.

## Criando uma Volume Claim persistente

Verifique se você está usando o wordpress-apps namespace:

```bash
kubectl config set-context --current --namespace=wordpress-apps
kubectl config get-contexts
```

Obtenha as Storage Classes presentes no cluster com:

```bash
kubectl get storageclasses
```

Anote o nome da classe disponível no seu Notepad (pois ela será usada no futuro).

Vá para a pasta do Lab04:

```bash
cd ~/kubernetes-lab/Lab04
```

Edite o arquivo wordpress-pvc.yml, alterando os valores onde for pedido. Aplique o arquivo e verifique se o PVC foi criado com:

```bash
kubectl get pvc
```

O status do PVC deve ser PENDING (isso mudará quando um pod for anexado nele). Anote o nome do PVC no seu Notepad, pois ele será usado no próximo exercício.

## Adicionando o PVC ao Pod de Wordpress atual

O mesmo arquivo de configuração que você usou no Lab 02 será alterado. Edite o arquivo com:

```bash
nano ~/kubernetes-lab/Lab02/wordpress-pod.yml
```

Adicione o seguinte conteúndo em “spec” para o pod:

```yaml
  volumes:
    - name: <PUT_HERE_A_NAME_FOR_THE_VOLUME>
      persistentVolumeClaim:
        claimName: <PUT_HERE_THE_PVC_NAME>
```

Adicione o seguinte conteúdo no “container” para o pod:

```yaml
    volumeMounts:
    - mountPath: /var/www/html
      name:  <VOLUME_NAME_JUST_CREATED>
```

Salve o arquivo pod.

Exclua o pod de WordPress executando:

```bash
kubectl delete pod \<WORDPRESS_POD_NAME\>
```

Aplique o arquivo que você acabou de criar, com o volume mount, para criar um novo pod:

```bash
kubectl apply -f ~/kubernetes-lab/Lab02/wordpress-pod.yml
```

Em um navegador web, teste se o site Wordpress está funcionando usando o http://\<STUDENT_WORDPRESS_URL\>. Execute as seguintes ações:

* Faça login no WordPress e crie uma nova publicação, subindo um arquivo de imagem nela. Confira se é possível ler a publicação e ver a imagem de forma correta.
* Exclua o pod de Wordpress e depois crie ele novamente.
* Verifique se a publicação que você acabou de criar ainda mostra a imagem que você enviou.

Se precisar de ajuda com as etapas anteriores, peça ajuda ao seu instrutor / instrutora.
