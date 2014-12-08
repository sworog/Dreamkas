package ru.dreamkas.jbehave;

import org.junit.Test;
import ru.dreamkas.apihelper.UUIDGenerator;

import static org.junit.Assert.assertThat;
import static org.hamcrest.Matchers.not;
import static org.hamcrest.Matchers.isEmptyString;
import static org.hamcrest.Matchers.containsString;

public class UUIDGeneratorTest {

    @Test
    public void testGenerateMethodReturnValueIsNotEmpty() {
        assertThat(new UUIDGenerator().generate(), not(isEmptyString()));
    }

    @Test
    public void testGenerateWithoutHyphensOutPutValueIsNotEmpty() {
        assertThat(UUIDGenerator.generateWithoutHyphens(), not(isEmptyString()));
    }

    @Test
    public void testGenerateWithoutHyphensOutPutContainsNoHyphens() {
        assertThat(UUIDGenerator.generateWithoutHyphens(), not(containsString("-")));
    }
}
