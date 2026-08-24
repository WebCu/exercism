"""Functions used in preparing Guido's gorgeous lasagna.

Learn about Guido, the creator of the Python language:
https://en.wikipedia.org/wiki/Guido_van_Rossum

This is a module docstring, used to describe the functionality
of a module and its functions and/or classes.
"""


EXPECTED_BAKE_TIME = 40
PREPARATION_TIME = 2


def bake_time_remaining(elapsed_bake_time):
    """Calculate the bake time remaining.

    Parameters:
        elapsed_bake_time (int): The baking time already elapsed.

    Returns:
        int: The remaining bake time (in minutes) derived from 'EXPECTED_BAKE_TIME'.

    Function that takes the actual minutes the lasagna has been in the oven as
    an argument and returns how many minutes the lasagna still needs to bake
    based on the `EXPECTED_BAKE_TIME`.
    """
    print(EXPECTED_BAKE_TIME)
    return EXPECTED_BAKE_TIME - elapsed_bake_time
    

def preparation_time_in_minutes(number_of_layers):
    """Calculate the preparation time for the number of layers.

    Parameters:
        number_of_layers (int): The number of layers

    Returns:
        int: The amount of time it will take to prepare the layers.

    Function that takes the actual number of layers as an argument
    and returns how many minutes it will take for the preparation
    based on the `PREPARATION_TIME`.
    """
    return PREPARATION_TIME * number_of_layers


def elapsed_time_in_minutes(number_of_layers, elapsed_baked_time):
    """Calculate the time elapsed.

    Parameters:
        number_of_layers (int): The number of layers.
        elapsed_bake_time (int): The baking time already elapsed.

    Returns:
        int: The elapsed time in minutes

    Function that takes the number of layers and the elapsed bake time as
    arguments and returns the preparation time in minutes.
    """
    return preparation_time_in_minutes(number_of_layers) + elapsed_baked_time