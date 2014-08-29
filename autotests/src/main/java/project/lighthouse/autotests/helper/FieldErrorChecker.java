package project.lighthouse.autotests.helper;

import org.hamcrest.Matchers;
import org.junit.Assert;
import org.openqa.selenium.By;
import org.openqa.selenium.NoSuchElementException;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonItem;
import project.lighthouse.autotests.elements.items.SelectByVisibleText;
import project.lighthouse.autotests.elements.items.autocomplete.AutoComplete;

public class FieldErrorChecker {

    private CommonItem commonItem;

    public FieldErrorChecker(CommonItem commonItem) {
        this.commonItem = commonItem;
    }

    public void assertFieldErrorMessage(String expectedFieldErrorMessage) {
        try {
            String actualFieldErrorMessage;
            WebElement fieldWebElement = commonItem.getVisibleWebElement();
            if (commonItem instanceof AutoComplete) {
                actualFieldErrorMessage = fieldWebElement.findElement(By.xpath("./../../*[contains(@class, 'form__errorMessage')]")).getText();
            } else if (commonItem instanceof SelectByVisibleText) {
                actualFieldErrorMessage = fieldWebElement.findElement(By.xpath("./../div[contains(@class, 'form__errorMessage')]")).getText();
            } else {
                actualFieldErrorMessage = fieldWebElement.findElement(By.xpath("./..")).getText();
            }
            Assert.assertThat(actualFieldErrorMessage, Matchers.is(expectedFieldErrorMessage));
        } catch (NoSuchElementException e) {
            Assert.fail("Field do not have error validation messages");
        }
    }
}
