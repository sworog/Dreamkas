package project.lighthouse.autotests.pages.store;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.collection.store.StoreObjectCollection;
import project.lighthouse.autotests.common.pageObjects.BootstrapPageObject;
import project.lighthouse.autotests.elements.bootstrap.buttons.PrimaryBtnFacade;

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
        putDefaultCollection(new StoreObjectCollection(getDriver()));
    }
}
