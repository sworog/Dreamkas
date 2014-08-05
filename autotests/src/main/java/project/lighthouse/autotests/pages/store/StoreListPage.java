package project.lighthouse.autotests.pages.store;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.BootstrapPageObject;
import project.lighthouse.autotests.elements.bootstrap.buttons.PrimaryBtnFacade;
import project.lighthouse.autotests.objects.web.store.StoreObjectCollection;

@DefaultUrl("/stores")
public class StoreListPage extends BootstrapPageObject {

    public StoreListPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void addObjectButtonClick() {
        new PrimaryBtnFacade(this, "Добавить магазин").click();
    }

    @Override
    public void createElements() {
    }

    public StoreObjectCollection getStoreObjectCollection() {
        return new StoreObjectCollection(getDriver());
    }
}
