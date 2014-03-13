package project.lighthouse.autotests.pages.departmentManager.order;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.Buttons.LinkFacade;
import project.lighthouse.autotests.elements.SelectByVisibleText;
import project.lighthouse.autotests.pages.departmentManager.order.pageElements.ProductAdditionForm;

/**
 * Page object class representing create/edit/view order page
 */
public class OrderPage extends CommonPageObject {

    /**
     * Page element representing product add form controls in order page
     */
    private ProductAdditionForm productAdditionForm;

    public OrderPage(WebDriver driver) {
        super(driver);
    }

    public ProductAdditionForm getProductAdditionForm() {
        return productAdditionForm;
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
    }

    public void cancelLinkClick() {
        new LinkFacade(this, "Отменить").click();
    }
}
