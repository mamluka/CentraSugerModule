require 'watir-webdriver'
require 'json'

desc "Install the module using module loader"
task :install do |task|

  `zsh -c 'zip -r package **/*.php'`
  `zsh -c 'zip -ru package **/*.json'`

  @driver = Watir::Browser.new :phantomjs

  config_file = File.dirname(__FILE__) + '/config-crm.json'
  config = JSON.parse(File.read(config_file))

  username ||= config['admin_username']
  password ||= config['admin_password']

  @driver.goto 'crmtesting.centracorporation.com'

  @driver.text_field(:name => 'user_name').set username
  @driver.text_field(:name => 'user_password').set password
  @driver.button(:name => 'Login').click

  @driver.link(:text => 'Admin').when_present.click
  @driver.link(:text => 'Module Loader').when_present.click
  if @driver.execute_script("return $('#installed_grid:contains(Centra Module)').length > 0;")
    @driver.execute_script("$('tr.yui-dt-rec:contains(Centra Module)').find('input[value=Uninstall]').click()")
    @driver.button(:value => 'Commit').when_present.click
    @driver.button(:value => 'Back to Module Loader').when_present.click
  end

  @driver.file_field(:name => 'upgrade_zip').when_present.set(File.dirname(__FILE__) + "/package.zip")
  @driver.button(:value => 'Upload').click
 
  @driver.execute_script("$('tr.yui-dt-rec:contains(Centra Module)').find('input[value=Install]').click()")
  @driver.button(:value => 'Commit').when_present.click

  File.delete(File.dirname(__FILE__)+'/package.zip')
end
