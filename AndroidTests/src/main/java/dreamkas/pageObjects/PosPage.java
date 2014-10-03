package dreamkas.pageObjects;

import net.thucydides.core.annotations.findby.FindBy;
import org.openqa.selenium.WebElement;

import java.util.List;

public class PosPage extends CommonPageObject {

    @FindBy(id = "android:id/home")
    private WebElement drawerIcon;

    @FindBy(id = "android:id/action_bar_title")
    private WebElement actionBarTitle;

    @FindBy(id = "ru.crystals.vaverjanov.dreamkas.debug:id/lstDrawer")
    private List<WebElement> lstDrawerList;

    @FindBy(id = "ru.crystals.vaverjanov.dreamkas.debug:id/btnSaveStoreSettings")
    private WebElement btnSaveStoreSettings;

    @FindBy(id = "ru.crystals.vaverjanov.dreamkas.debug:id/spStores")
    private List<WebElement> spStores;

    @FindBy(id = "ru.crystals.vaverjanov.dreamkas.debug:id/lblStore")
    WebElement lblStore;

    public String getActionBarTitle() {
        return actionBarTitle.getText();
    }
}
