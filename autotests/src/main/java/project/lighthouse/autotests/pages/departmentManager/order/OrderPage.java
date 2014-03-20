package project.lighthouse.autotests.pages.departmentManager.order;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.Buttons.LinkFacade;
import project.lighthouse.autotests.elements.SelectByVisibleText;
import project.lighthouse.autotests.elements.preLoader.PreLoader;
import project.lighthouse.autotests.objects.web.order.orderProduct.OrderProductObjectCollection;
import project.lighthouse.autotests.pages.departmentManager.order.pageElements.ProductAdditionForm;
import project.lighthouse.autotests.pages.departmentManager.order.pageElements.ProductEditionForm;

/**
 * Page object class representing create/edit/view order page
 */
@DefaultUrl("/orders/create")
public class OrderPage extends CommonPageObject {

    /**
     * Page element representing product addition form controls in order page
     */
    @SuppressWarnings("unused")
    private ProductAdditionForm productAdditionForm;

    @SuppressWarnings("unused")
    private ProductEditionForm productEditionForm;

    public OrderPage(WebDriver driver) {
        super(driver);
    }

    public ProductAdditionForm getProductAdditionForm() {
        return productAdditionForm;
    }

    public ProductEditionForm getProductEditionForm() {
        return productEditionForm;
    }

    @Override
    public void createElements() {
        getItems().put("supplier", new SelectByVisibleText(this, "supplier", "Поставщик"));
    }

    public String getOrderTotalSumText() {
        return findVisibleElement(By.className("form_order__totalSum")).getText();
    }

    public void saveButtonClick() {
        new ButtonFacade(this, "Сохранить").click();
        new PreLoader(getDriver()).await();
    }

    public void cancelLinkClick() {
        new LinkFacade(this, "Отменить").click();
    }

    public void deleteButtonClick() {
        new ButtonFacade(this, "Удалить").click();
    }

    public OrderProductObjectCollection getOrderProductObjectCollection() {
        return new OrderProductObjectCollection(getDriver(), By.name("orderProduct"));
    }

    public String getOrderNumberHeaderText() {
        return findVisibleElement(By.xpath("//*[@class='page__data']/h2")).getText();
    }
}
