# Lab 04

On this lab, we will work with data persistence on the Wordpress Pod created on Lab 02.

## Create a Persistent Volume Claim

Make sure the wordpress-apps namespace is being used:

```bash
kubectl config set-context --current --namespace=wordpress-apps
kubectl config get-contexts
```

Get the Storage Classes present on the cluster with:

```bash
kubectl get storageclasses
```

Write on the notepad the name of the class available, as it will be used later.

Move to the Lab04 folder:

```bash
cd ~/kubernetes-lab/Lab04
```

Edit the wordpress-pvc.yml file, changing the values where requested. Apply the file and check if the PVC have been created with:

```bash
kubectl get pvc
```

Write the PVC name on the notepad as it will be used on the next task.

## Add the PVC to the actual Wordpress Pod

The same configuration file used on Lab 02 will be changed. Edit the file with:

```bash
nano ~/kubernetes-lab/Lab02/wordpress-pod.yml
```

Add the following under the "spec" for the pod:

```yaml
  volumes:
    - name: \<PUT_HERE_A_NAME_FOR_THE_VOLUME\>
      persistentVolumeClaim:
        claimName: \<PUT_HERE_THE_PVC_NAME\>
```

Add the following under the "container" for the pod:

```yaml
    volumeMounts:
    - mountPath: /var/www/html
      name:  \<VOLUME_NAME_JUST_CREATED\>
```

Save the pod file and apply it again.
