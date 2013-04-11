require 'watir-webdriver'
require 'json'
require 'headless'

desc "Install the module using module loader"
task :install do |task|

  `zsh -c 'zip -r package **/*.php'`
  `zsh -c 'zip -ru package **/*.js'`
  `zsh -c 'zip -ru package **/*.css'`
  `zsh -c 'zip -ru package **/*.json'`

  config_file = File.dirname(__FILE__) + '/config-crm.json'
  config = JSON.parse(File.read(config_file))
  base_url = config['base_url']

  @headless = Headless.new
  @headless.start

  @driver = Watir::Browser.start base_url



  username ||= config['admin_username']
  password ||= config['admin_password']

  @driver.text_field(:name => 'user_name').set username
  @driver.text_field(:name => 'user_password').set password
  @driver.button(:name => 'Login').click

  puts "Logged in to the CRM"

  @driver.link(:text => 'Admin').when_present.click
  @driver.link(:text => 'Module Loader').when_present.click
  if @driver.execute_script("return $('#installed_grid:contains(Centra Module)').length > 0;")
    puts "Uninstalling current version"

    @driver.execute_script("$('tr.yui-dt-rec:contains(Centra Module)').find('input[value=Uninstall]').click()")
    @driver.button(:value => 'Commit').when_present.click
    @driver.button(:value => 'Back to Module Loader').when_present.click
  end

  puts "Uploading file.."

  @driver.file_field(:name => 'upgrade_zip').when_present.set(File.dirname(__FILE__) + "/package.zip")
  @driver.button(:value => 'Upload').click

  puts "Waiting to upload"

  sleep 1
 
  @driver.execute_script("$('tr.yui-dt-rec:contains(Centra Module)').find('input[value=Install]').click()")
  @driver.button(:value => 'Commit').when_present.click

  puts "Installed"

  File.delete(File.dirname(__FILE__)+'/package.zip')

  @driver.close
  @headless.destroy
end
