package project.lighthouse.autotests.pages.catalog.group;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.collection.product.ProductCollection;
import project.lighthouse.autotests.common.objects.BootstrapPageObject;
import project.lighthouse.autotests.elements.bootstrap.buttons.PrimaryBtnFacade;

/**
 * Group page object
 */
public class GroupPage extends BootstrapPageObject {

    public GroupPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void addObjectButtonClick() {
        new PrimaryBtnFacade(this, "Добавить товар").click();
    }

    @Override
    public void createElements() {
    }

    public void editGroupIconClick() {
        // TODO Wrap to bootstrap element
        findVisibleElement(By.xpath("//*[@class='fa fa-edit']")).click();
    }

    public void longArrowBackLinkClick() {
        // TODO Wrap to bootstrap element
        findVisibleElement(By.xpath("//*[@class='fa fa-long-arrow-left']")).click();
    }

    public ProductCollection getProductCollection() {
        return new ProductCollection(getDriver(), By.className("product__link"));
    }

    public void sortByNameClick() {
        findVisibleElement(By.xpath("//*[@data-products-sort-by='name']")).click();
    }

    public void sortBySellingPriceClick() {
        findVisibleElement(By.xpath("//*[@data-products-sort-by='name']")).click();
    }

    public void sortByBarcodeClick() {
        findVisibleElement(By.xpath("//*[@data-products-sort-by='name']")).click();
    }
}
