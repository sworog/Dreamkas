package project.lighthouse.autotests.pages.catalog.group;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.objects.web.product.ProductCollection;
import sun.reflect.generics.reflectiveObjects.NotImplementedException;

/**
 * Group page object
 */
public class GroupPage extends CommonPageObject {

    public GroupPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public String getGroupTitle() {
        return findVisibleElement(By.className("page-title")).getText();
    }

    public void editGroupIconClick() {
        // TODO Wrap to bootstrap element
        findVisibleElement(By.xpath("//*[@class='fa fa-edit']")).click();
    }

    public void longArrowBackLinkClick() {
        // TODO Wrap to bootstrap element
        findVisibleElement(By.xpath("//*[@class='fa fa-long-arrow-left']")).click();
    }

    public void createNewProductButtonClick() {
        throw new NotImplementedException();
    }

    public ProductCollection getProductCollection() {
        return new ProductCollection(getDriver(), By.name("product"));
    }
}
