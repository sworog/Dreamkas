package project.lighthouse.autotests.pages.administrator;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.Input;
import project.lighthouse.autotests.elements.PreLoader;

@DefaultUrl("/settings")
public class SettingsPage extends CommonPageObject {

    public SettingsPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        items.put("set10-integration-url", new Input(this, "set10-integration-url"));
        items.put("set10-integration-login", new Input(this, "set10-integration-login"));
        items.put("set10-integration-password", new Input(this, "set10-integration-password"));

        items.put("set10-import-url", new Input(this, "set10-import-url"));
        items.put("set10-import-login", new Input(this, "set10-import-login"));
        items.put("set10-import-password", new Input(this, "set10-import-password"));
        items.put("set10-import-interval", new Input(this, "set10-import-interval"));
    }

    public void saveSettingButtonClick() {
        new ButtonFacade(getDriver(), "Сохранить настройки экспорта").click();
        new PreLoader(getDriver()).await();
    }

    public void saveImportSettingsClick() {
        new ButtonFacade(getDriver(), "Сохранить настройки импорта").click();
        new PreLoader(getDriver()).await();
    }
}
