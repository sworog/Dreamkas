package project.lighthouse.autotests.pages.catalog;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.collection.catalog.GroupObjectCollection;
import project.lighthouse.autotests.common.objects.BootstrapPageObject;
import project.lighthouse.autotests.elements.bootstrap.buttons.PrimaryBtnFacade;

/**
 * Catalog page object
 */
@DefaultUrl("/catalog")
public class CatalogPage extends BootstrapPageObject {

    public CatalogPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void addObjectButtonClick() {
        new PrimaryBtnFacade(this, "Добавить группу").click();
    }

    @Override
    public void createElements() {
    }

    public GroupObjectCollection getGroupObjectCollection() {
        return new GroupObjectCollection(getDriver(), By.className("groupList__link"));
    }
}
