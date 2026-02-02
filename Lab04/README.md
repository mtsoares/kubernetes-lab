# Lab 04

In this lab, we will work with data persistence on the Wordpress Pod we have created in Lab 02.

## Creating a Persistent Volume Claim

Make sure you are using the wordpress-apps namespace:

```bash
kubectl config set-context --current --namespace=wordpress-apps
kubectl config get-contexts
```

Get the Storage Classes present on the cluster with:

```bash
kubectl get storageclasses
```

Write down the name of the class available on your notepad, as it will be used later.

Move to the folder for Lab04:

```bash
cd ~/kubernetes-lab/Lab04
```

Edit the wordpress-pvc.yml file, changing the values where requested. Apply the file and check if the PVC was created with:

```bash
kubectl get pvc
```

The PVC status should be PENDING (this will change once a pod is attached to it). Write down the PVC name on the notepad as it will be used in the next task.

## Adding the PVC to the current Wordpress Pod

The same configuration file used on Lab 02 will be changed. Edit the file with:

```bash
nano ~/kubernetes-lab/Lab02/wordpress-pod.yml
```

Add the following content under the "spec" for the pod:

```yaml
  volumes:
    - name: <PUT_HERE_A_NAME_FOR_THE_VOLUME>
      persistentVolumeClaim:
        claimName: <PUT_HERE_THE_PVC_NAME>
```

Add the following content under the "container" for the pod:

```yaml
    volumeMounts:
    - mountPath: /var/www/html
      name:  <VOLUME_NAME_JUST_CREATED>
```

Save the pod file.

Delete the wordpress pod by running:

```bash
kubectl delete pod \<WORDPRESS_POD_NAME\>
```

Apply the file you just created, with the volume mount, so a new pod is created:

```bash
kubectl apply -f ~/kubernetes-lab/Lab02/wordpress-pod.yml
```

In a web browser, test if the Wordpress site is working using the http://\<STUDENT_WORDPRESS_URL\>. Perform the following actions:

* Login to wordpress and create a new post uploading an image file to it. Check if the post can be read and the image can be displayed.
* Delete the Wordpress pod, and recreate it again.
* Check if the page you just created still displays the uploaded image.

If you need any help with the previous steps, ask your instructor.
