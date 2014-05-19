package project.lighthouse.autotests.helper;

import org.hamcrest.Matchers;
import org.junit.Assert;
import org.openqa.selenium.By;
import org.openqa.selenium.NoSuchElementException;
import project.lighthouse.autotests.common.CommonItem;

public class FieldErrorChecker {

    private CommonItem commonItem;

    public FieldErrorChecker(CommonItem commonItem) {
        this.commonItem = commonItem;
    }

    public void assertFieldErrorMessage(String expectedFieldErrorMessage) {
        try {
            String actualFieldErrorMessage = commonItem.getVisibleWebElement().findElement(By.xpath("./../../*[@data-error]")).getAttribute("data-error");
            Assert.assertThat(actualFieldErrorMessage, Matchers.is(expectedFieldErrorMessage));
        } catch (NoSuchElementException e) {
            Assert.fail("Field do not have error validation messages");
        }
    }
}
