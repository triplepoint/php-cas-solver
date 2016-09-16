<?php
namespace CAS;

class RelationSolver
{
    protected $relation;

    public function __construct(Relation $relation)
    {
        $this->relation = $relation;
    }

    public function solveFor($variable)
    {
        // 1 - Expand all the terms in the relation out, so that we have a 1st order
        // polynomial (don't worry about reducing like terms).  If it looks like a higher order
        // polynomial, throw an exception (for now).: expandExpression()
        $new_relation = new Relation(
            $this->expandExpression($this->relation->lhs),
            $this->relation->operator,
            $this->expandExpression($this->relation->rhs)
        );

        // 2 - Move all the terms with the target variable in them to the left hand
        // side, and everything else to the right
        // if there aren't any, throw an exception "unknown variable"

        // 3 - factor out the target variable on the left hand side: factorExpressionFor('x')
        $new_relation = new Relation(
            $this->factorExpressionFor($new_relation->lhs, $variable),
            $new_relation->operator,
            $new_relation->rhs
        );

        // 4 - Divide both sides by the factor of the target variable


        // Example
        // (z * ( 4 + x)) = (6 * y) + (5 * x)      // starting example
        // (z * 4) + (z * x) = (6 * y) + (5 * x)   // 1) here, it would throw an exception if there was somthing like x * x
        // (z * x) - (5 * x) = (6 * y) - (z * 4)   // 2)
        // (x * (z - 5)) = (6 * y) - (z * 4)       // 3)
        // x = ((6 * y) - (z * 4)) / (z - 5)       // 4)

        return $new_relation;
    }

    public function expandExpression(Expression $expression)
    {
        return $expression;

        // Take something like:
        //  "2 * x * (4 + y)"     =>   "((2 * x) * 4) + ((2 * x) * y)"
    }

    public function factorExpressionFor(Expression $expression, $variable)
    {
        return $expression;

        // Take something like:
        //  "(z * x)"                        =>   "(z * x)"
        //  "(z * x) - (5 * x)"              =>   "(x * (z - 5))"
        //  "((2 * x) * 4) + ((2 * x) * y)"  =>   "(x * ((2 * 4) + (2 * y))"
    }
}
