package project.lighthouse.autotests.pages.catalog;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.objects.web.catalog.GroupObjectCollection;

/**
 * Catalog page object
 */
public class CatalogPage extends CommonPageObject {

    public CatalogPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public void addGroupButtonClick() {
        //Добавить группу
    }

    public GroupObjectCollection getGroupObjectCollection() {
        return new GroupObjectCollection(getDriver(), By.name("group"));
    }

    public String getGroupHeadingText() {
        return findVisibleElement(By.name("groupHeading")).getText();
    }

    public String getHeadingText() {
        return findVisibleElement(By.name("Heading")).getText();
    }
}
