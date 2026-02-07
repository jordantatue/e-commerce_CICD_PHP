# Sauvegarde
docker run --rm -v NOM_VOLUME:/data -v $(pwd):/backup alpine tar czf /backup/sauvegarde.tar.gz -C /data .

# Ou avec un conteneur en cours
docker run --rm --volumes-from NOM_CONTAINER -v $(pwd):/backup alpine tar czf /backup/sauvegarde.tar.gz -C /chemin/du/volume .


docker run --rm -v NOM_VOLUME:/data -v $(pwd):/backup alpine tar xzf /backup/sauvegarde.tar.gz -C /data