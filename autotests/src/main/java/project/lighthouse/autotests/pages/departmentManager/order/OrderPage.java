package project.lighthouse.autotests.pages.departmentManager.order;

import net.thucydides.core.annotations.DefaultUrl;
import net.thucydides.core.annotations.findby.FindBy;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.Buttons.LinkFacade;
import project.lighthouse.autotests.elements.items.NewAutoComplete;
import project.lighthouse.autotests.elements.items.SelectByVisibleText;
import project.lighthouse.autotests.objects.web.order.orderProduct.OrderProductObjectCollection;

/**
 * Page object class representing create/edit/view order page
 */
@DefaultUrl("/orders/create")
public class OrderPage extends CommonPageObject {

    @FindBy(xpath = "//*[@class='page__data']/h2")
    @SuppressWarnings("unused")
    WebElement orderNumberHeaderTextWebElement;

    @FindBy(xpath = "//*[@class='form_order__saveControls']/p")
    @SuppressWarnings("unused")
    WebElement saveControlsTextWebElement;

    public OrderPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        getItems().put("supplier", new SelectByVisibleText(this, "supplier", "Поставщик"));
        getItems().put("order product autocomplete", new NewAutoComplete(this, By.xpath("//*[@class='inputText autocomplete']")));
    }

    public String getOrderTotalSumText() {
        return findVisibleElement(By.className("form_order__totalSum")).getText();
    }

    public ButtonFacade getSaveButton() {
        return new ButtonFacade(this, "Сохранить");
    }

    public ButtonFacade getOrderAcceptButton() {
        return new ButtonFacade(this, "Принять по заказу");
    }

    public LinkFacade getCancelLink() {
        return new LinkFacade(this, "Отменить");
    }

    public void deleteButtonClick() {
        new LinkFacade(this, "Удалить").click();
    }

    public OrderProductObjectCollection getOrderProductObjectCollection() {
        return new OrderProductObjectCollection(getDriver(), By.name("orderProduct"));
    }

    public String getOrderNumberHeaderText() {
        return findVisibleElement(orderNumberHeaderTextWebElement).getText();
    }

    public LinkFacade getDownloadFileLink() {
        return new LinkFacade(this, "Скачать файл заказа");
    }

    public String getSaveControlsText() {
        return findVisibleElement(saveControlsTextWebElement).getText();
    }
}
