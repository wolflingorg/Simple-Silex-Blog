# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|
  config.vm.box = "ubuntu/xenial64"
  config.vm.box_version = "20170626.0.0"

  config.vm.provider "virtualbox" do |v|
    v.memory = 1024
    v.cpus = 1
  end

  config.vm.box_check_update = false

  config.vm.network "private_network", ip: "192.168.10.10"

  config.vm.provision :shell, path: "bootstrap.sh"
end
