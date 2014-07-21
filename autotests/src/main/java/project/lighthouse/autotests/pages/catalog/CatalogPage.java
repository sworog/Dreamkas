package project.lighthouse.autotests.pages.catalog;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.bootstrap.buttons.PrimaryBtnFacade;
import project.lighthouse.autotests.objects.web.catalog.GroupObjectCollection;

/**
 * Catalog page object
 */
@DefaultUrl("/catalog")
public class CatalogPage extends CommonPageObject {

    public CatalogPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public void addGroupButtonClick() {
        new PrimaryBtnFacade(this, "Добавить группу").click();
    }

    public GroupObjectCollection getGroupObjectCollection() {
        return new GroupObjectCollection(getDriver(), By.className("groupList__link"));
    }

    public String getTitle() {
        return findVisibleElement(By.className("page-title")).getText();
    }
}
