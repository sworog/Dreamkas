package project.lighthouse.autotests.pages.catalog.group.modal;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.bootstrap.buttons.PrimaryBtnFacade;
import project.lighthouse.autotests.elements.items.Input;
import project.lighthouse.autotests.elements.items.NonType;
import project.lighthouse.autotests.elements.items.SelectByVisibleText;
import project.lighthouse.autotests.elements.items.autocomplete.AutoComplete;
import project.lighthouse.autotests.handler.field.FieldErrorChecker;
import project.lighthouse.autotests.pages.modal.ModalWindowPage;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

/**
 * Create new product modal window
 */
public class ProductCreateModalWindow extends ModalWindowPage {

    public ProductCreateModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("group", new AutoComplete(this, "//*[@class='select2-choice']"));
        put("name", getInputWithCustomFieldErrorChecker("//*[@name='name']"));
        put("unit", new Input(this, "//*[@name='units']"));
        put("barcode", getInputWithCustomFieldErrorChecker("//*[@name='barcode']"));
        put("vat", new SelectByVisibleText(this, "//*[@name='vat']"));
        put("purchasePrice", new Input(this, "//*[@name='purchasePrice']"));
        put("sellingPrice", new Input(this, "//*[@name='sellingPrice']"));
        put("markUpValue", new NonType(this, "//*[contains(@class, 'product__markup')]"));
    }

    @Override
    public void confirmationOkClick() {
        new PrimaryBtnFacade(this, "Добавить").click();
    }

    @Override
    public String modalWindowXpath() {
        return "//*[@id='modal_product']";
    }

    @Override
    public String getTitle() {
        return findVisibleElement(By.xpath(modalWindowXpath() + "//*[@class='modal__title']")).getText();
    }

    private Input getInputWithCustomFieldErrorChecker(String xpath) {
        return new Input(this, xpath) {

            @Override
            public FieldErrorChecker getFieldErrorMessageChecker() {
                return new FieldErrorChecker(this) {
                    @Override
                    public void assertFieldErrorMessage(String expectedFieldErrorMessage) {
                        String actualFieldErrorMessage = this.getCommonItem().getVisibleWebElement().findElement(By.xpath("./../*[contains(@class, 'form__errorMessage')]")).getText();
                        assertThat(actualFieldErrorMessage, is(expectedFieldErrorMessage));
                    }
                };
            }
        };
    }
}
