# Git SSH push workaround (coder oturumlarının HOME'u /opt/data/profiles/coder/home — ssh config /opt/data/home altında)
cd /opt/data/workspace/proje
GIT_SSH_COMMAND="ssh -F /opt/data/home/.ssh/config -i /opt/data/home/.ssh/sutre_deploy -o UserKnownHostsFile=/opt/data/home/.ssh/known_hosts" git push origin main
