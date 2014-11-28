package ru.dreamkas.pages.stockMovement.modal;

import org.hamcrest.Matchers;
import org.junit.Assert;
import org.openqa.selenium.By;
import org.openqa.selenium.NoSuchElementException;
import org.openqa.selenium.WebDriver;
import ru.dreamkas.apihelper.DateTimeHelper;
import ru.dreamkas.collection.abstractObjects.AbstractObjectCollection;
import ru.dreamkas.elements.bootstrap.buttons.PrimaryBtnFacade;
import ru.dreamkas.elements.bootstrap.buttons.TransparentBtnFacade;
import ru.dreamkas.elements.items.DateInput;
import ru.dreamkas.elements.items.Input;
import ru.dreamkas.elements.items.NonType;
import ru.dreamkas.elements.items.SelectByVisibleText;
import ru.dreamkas.elements.items.autocomplete.ProductAutoComplete;
import ru.dreamkas.handler.field.FieldChecker;
import ru.dreamkas.handler.field.FieldErrorChecker;
import ru.dreamkas.pages.modal.ModalWindowPage;
import ru.dreamkas.pages.stockMovement.modal.invoice.InvoiceCreateModalWindow;

public abstract class StockMovementModalPage extends ModalWindowPage {

    public StockMovementModalPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("date", getCustomDateInput());
        put("store", new SelectByVisibleText(this, "//*[@name='store']"));
        put("product.name", new ProductAutoComplete(this, "//*[@name='product.name' and contains(@class, 'input')]"));
        put("price", new Input(this, "//*[@name='price']"));
        put("quantity", new Input(this, "//*[@name='quantity']"));

        put("заголовок успешного удаления", new NonType(this, "//*[@name='successRemoveTitle']"));
        put("кнопка 'Создать магазин'", new TransparentBtnFacade(this, "Создать магазин"));
    }

    public abstract AbstractObjectCollection getProductCollection();

    public String getTotalSum() {
        String xpath = String.format("%s//*[contains(@class, 'totalPrice') and @name='totalPrice']", modalWindowXpath());
        return findVisibleElement(By.xpath(xpath)).getText();
    }

    public void addProductButtonClick() {
        clickInTheModalWindowByXpath("//*[contains(@class, 'btn btn-default')]");
    }

    public abstract Integer getProductRowsCount();

    protected PrimaryBtnFacade getConfirmationOkButton(String buttonLabel) {
        return new PrimaryBtnFacade(this, buttonLabel);
    }

    protected Integer getProductRowsCount(String tableClass) {
        String cssSelector = String.format("table.%s tbody>tr", tableClass);
        return getDriver().findElements(By.cssSelector(cssSelector)).size();
    }

    @Override
    public String getTitle() {
        return findVisibleElement(By.xpath(modalWindowXpath() + "//*[@class='modal__title']")).getText();
    }

    private DateInput getCustomDateInput() {
        return new DateInput(this, "//*[@name='date']") {

            @Override
            public void setValue(String value) {
                String convertedValue = DateTimeHelper.getDate(value);
                super.setValue(convertedValue);
            }

            @Override
            public FieldChecker getFieldChecker() {
                return new FieldChecker(this) {

                    @Override
                    public void assertValueEqual(String expectedValue) {
                        String convertedValue = DateTimeHelper.getDate(expectedValue);
                        super.assertValueEqual(convertedValue);
                    }
                };
            }

            @Override
            public FieldErrorChecker getFieldErrorMessageChecker() {
                return new FieldErrorChecker(this) {

                    @Override
                    public void assertFieldErrorMessage(String expectedFieldErrorMessage) {
                        try {
                            String actualFieldErrorMessage;
                            if (getPageObject() instanceof InvoiceCreateModalWindow) {
                                actualFieldErrorMessage = getCommonItem().getVisibleWebElement().findElement(By.xpath("./../../*[contains(@class, 'form__errorMessage')]")).getText();
                            } else {
                                actualFieldErrorMessage = getCommonItem().getVisibleWebElement().findElement(By.xpath("./../*[contains(@class, 'form__errorMessage')]")).getText();
                            }
                            Assert.assertThat(actualFieldErrorMessage, Matchers.is(expectedFieldErrorMessage));
                        } catch (NoSuchElementException e) {
                            Assert.fail("Field do not have error validation messages");
                        }
                    }
                };
            }
        };
    }
}
