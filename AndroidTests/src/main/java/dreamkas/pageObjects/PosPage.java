package dreamkas.pageObjects;

import net.thucydides.core.annotations.findby.By;
import net.thucydides.core.annotations.findby.FindBy;
import org.openqa.selenium.WebElement;

import java.util.List;

public class PosPage extends CommonPageObject {

    @FindBy(id = "android:id/home")
    private WebElement drawerIcon;

    @FindBy(id = "android:id/action_bar_title")
    private WebElement actionBarTitle;

    @FindBy(id = "ru.crystals.vaverjanov.dreamkas.debug:id/lstDrawer")
    private WebElement lstDrawer;

    @FindBy(id = "ru.crystals.vaverjanov.dreamkas.debug:id/btnSaveStoreSettings")
    private WebElement btnSaveStoreSettings;

    @FindBy(id = "ru.crystals.vaverjanov.dreamkas.debug:id/spStores")
    private WebElement spStores;

    @FindBy(id = "android:id/text1")
    private List<WebElement> spinnerElements;

    @FindBy(id = "ru.crystals.vaverjanov.dreamkas.debug:id/lblStore")
    WebElement lblStore;

    public String getActionBarTitle() {
        return actionBarTitle.getText();
    }

    public void chooseSpinnerItemWithValue(String value) {
        spStores.click();
        clickOnElementWithText(spinnerElements, value);
    }

    public void clickOnSaveStoreSettings() {
        btnSaveStoreSettings.click();
    }

    public String getStore() {
        return lblStore.getText();
    }

    public void openDrawerAndClickOnDrawerOption(String menuOption) {
        drawerIcon.click();
        clickOnElementWithText(getAppiumDriver().findElements(By.xpath("//android.widget.TextView")), menuOption);
    }
}
